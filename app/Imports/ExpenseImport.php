<?php

namespace App\Imports;

use App\Models\Expenses;
use App\Models\ExpensesItem;
use App\Models\ExpensesCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;

class ExpenseImport implements ToCollection, WithHeadingRow, WithGroupedHeadingRow
{
    public function __construct()
    {
        // Constructor if needed
    }

        public function collection(Collection $rows)
    {
        $data = $rows->toArray();

        // Validate before processing (removed category exists rule)
        $validator = Validator::make($data, [
            '*.category' => ['required', 'string', 'max:255'],
            '*.title' => ['required', 'string', 'max:255'],
            '*.date' => ['required', 'date'],
            '*.notes' => ['nullable', 'string', 'max:500'],
            '*.item_name' => ['required', 'string', 'max:255'],
            '*.item_qty' => ['required', 'numeric'],
            '*.amount' => ['required', 'numeric'],
            '*.note' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->validate();

        try {
            DB::beginTransaction();

            // Process rows in chunks
            foreach (array_chunk($data, 500) as $chunk) {
                $grouped = collect($chunk)->groupBy('title');

                foreach ($grouped as $title => $items) {
                    $firstItem = $items->first();

                    // Automatically create category if not found
                    $category = ExpensesCategory::firstOrCreate(
                        ['name' => $firstItem['category']],
                    );

                    $expense = Expenses::create([
                        'category_id' => $category->id,
                        'title' => $title,
                        'date' => $firstItem['date'],
                        'total' => 0,
                        'notes' => $firstItem['notes'] ?? null,
                    ]);

                    $total = 0;

                    foreach ($items as $item) {
                        $qty = (float)$item['item_qty'];
                        $amount = (float)$item['amount'];
                        $total += $qty * $amount;

                        ExpensesItem::create([
                            'expenses_id' => $expense->id,
                            'item_name' => $item['item_name'],
                            'item_qty' => $qty,
                            'amount' => $amount,
                            'note' => $item['note'] ?? null,
                        ]);
                    }

                    $expense->update(['total' => $total]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

