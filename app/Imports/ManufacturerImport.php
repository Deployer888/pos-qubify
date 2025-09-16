<?php

namespace App\Imports;

use App\Models\Manufacturer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Traits\HasImageImporter;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ManufacturerImport implements ToCollection, WithHeadingRow
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
                ? $this->importImage($rowArray['image'], null, 'manufacturers')
                : null;

            Manufacturer::create([
                'name' => $rowArray['name'],
                'desc' => $rowArray['desc'] ?? null,
                'image' => $imagePath,
                'status' => $rowArray['status'] ?? Manufacturer::STATUS_ACTIVE,
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
                Rule::unique('manufacturers', 'name')
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
                Rule::in([Manufacturer::STATUS_ACTIVE, Manufacturer::STATUS_INACTIVE])
            ]
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.unique' => 'The manufacturer ":input" already exists.',
            'name.required' => 'The name is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status value. Allowed: active, inactive.',
        ];
    }
}
