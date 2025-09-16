<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * PaymentsReportExport
 */
class PaymentsReportExport implements FromArray, WithHeadings, ShouldAutoSize
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
            $r[] =  make8digits($item->invoice_id);
            $r[] = $item->date;
            $r[] = $item->payment_type;
            $r[] = $item->amount;

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
            __('custom.invoice_id'),
            __('custom.date'),
            __('custom.payment_type'),
            __('custom.total')
        ];
    }
}
