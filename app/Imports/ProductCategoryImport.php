<?php

namespace App\Imports;

use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Traits\HasImageImporter;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductCategoryImport implements ToCollection, WithHeadingRow
{
    use Importable, HasImageImporter;

    public function collection(Collection $rows)
    {
        $rowNumber = 2; // heading is row 1
        $errors = [];

        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            // Validate row
            $validator = Validator::make($rowArray, $this->rules(), $this->customValidationMessages());

            if ($validator->fails()) {
                $errorMsgs = $validator->errors()->all();
                $errors[] = "Row {$rowNumber}: " . implode(', ', $errorMsgs);
            }

            $rowNumber++;
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        // If validation passes for all rows, then insert
        $rowNumber = 2;
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $imagePath = !empty($rowArray['image'])
                ? $this->importImage($rowArray['image'], null, 'product_categories')
                : null;

                $parent_category = trim($rowArray['parent_category'] ?? '');
                $parent_id = null;
                if ($parent_category) {
                    $parent = ProductCategory::whereRaw('LOWER(name) = ?', [strtolower($parent_category)])->first();

                    // If parent not found, create it
                    if (!$parent) {
                        $parent = ProductCategory::create([
                            'name' => $parent_category,
                            'status' => ProductCategory::STATUS_ACTIVE,
                            'created_by' => Auth::id()
                        ]);
                    }

                    $parent_id = $parent->id;
                }


            ProductCategory::create([
                'name' => $rowArray['name'],
                'desc' => $rowArray['desc'] ?? null,
                'image' => $imagePath,
                'status' => $rowArray['status'] ?? ProductCategory::STATUS_ACTIVE,
                'parent_id' => $parent_id,
                'created_by' => Auth::id(),
            ]);

            $rowNumber++;
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
                Rule::unique('product_categories', 'name')
            ],
            'desc' => [
                'nullable',
                'max:255'
            ],
            'image' => [
                'nullable',
                'string'
            ],
            'status' => [
                'required',
                Rule::in([ProductCategory::STATUS_ACTIVE, ProductCategory::STATUS_INACTIVE])
            ]
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.unique' => 'The product category ":input" already exists.',
            'name.required' => 'The name is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status value. Allowed: active, inactive.',
        ];
    }
}
