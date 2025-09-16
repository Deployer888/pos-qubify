<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * PurchasesReportExport
 */
class PurchasesReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $items;

    /**
     * __construct
     *
     * @param  mixed $items
     * @return void
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * array
     *
     * @return array
     */
    public function array(): array
    {
        $data = [];

        $sl = 1;
        foreach ($this->items as $item) {
            $r = [];
            $r[] = $sl;
            $r[] = $item->purchase_number;
            $r[] = custom_date($item->date);
            $r[] = $item->supplier->first_name ?? '';
            $r[] = $item->total;
            $r[] = $item->notes;

            $data[] = $r;

            $sl++;
        }

        return $data;
    }

    /**
     * headings
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            __('custom.purchase_number'),
            __('custom.date'),
            __('custom.supplier'),
            __('custom.total'),
            __('custom.notes')
        ];
    }
}
