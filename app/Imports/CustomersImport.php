<?php

namespace App\Imports;

use App\Models\Depot;
use App\Models\DepotCustomer;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $depot;

    public function __construct(Depot $depot)
    {
        $this->depot = $depot;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DepotCustomer::create([
                'depot_id' => $this->depot->id,
                'family_id' => $row['family_id'],
                'adhaar_no' => $row['adhaar_no'],
                'ration_card_no' => $row['ration_card_no'],
                'card_range' => $row['card_range'],
                'name' => $row['name'],
                'mobile' => $row['mobile'],
                'age' => $row['age'],
                'is_family_head' => $row['is_family_head'] ?? false,
                'address' => $row['address'],
                'status' => $row['status'] ?? 'active'
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'family_id' => 'required|string|max:255',
            'adhaar_no' => [
                'required',
                'string',
                'size:12',
                Rule::unique('depot_customers', 'adhaar_no')
            ],
            'ration_card_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('depot_customers', 'ration_card_no')
            ],
            'card_range' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'age' => 'required|integer|min:0|max:150',
            'is_family_head' => 'nullable|boolean',
            'address' => 'required|string',
            'status' => ['nullable', Rule::in(['active', 'inactive'])]
        ];
    }
}
