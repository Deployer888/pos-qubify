<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * ExpiredProductsExport
 */
class ExpiredProductsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $items;

    /**
     * Constructor
     *
     * @param  mixed $items
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * Prepare array for export
     *
     * @return array
     */
    public function array(): array
    {
        $data = [];

        $sl = 1;
        foreach ($this->items as $item) {
            $data[] = [
                $sl++,
                $item->product?->name ?? '',
                $item->warehouse?->name ?? '',
                $item->batch,
                $item->expiry_date,
                $item->quantity !== null ? (string) $item->quantity : '0'
            ];
        }

        return $data;
    }

    /**
     * Define column headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            __('custom.product'),
            __('custom.warehouse'),
            __('custom.batch'),
            __('custom.expiry_date'),
            __('custom.quantity'),
        ];
    }
}
