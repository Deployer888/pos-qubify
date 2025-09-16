<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * ExpensesReportExport
 */
class ExpensesReportExport implements FromArray, WithHeadings, ShouldAutoSize
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
            $r[] = $item->date;
            $r[] = $item->title;
            $r[] = $item->category->name;
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
            __('custom.date'),
            __('custom.title'),
            __('custom.category'),
            __('custom.total'),
            __('custom.notes')
        ];
    }
}
