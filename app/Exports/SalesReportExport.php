<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * SalesReportExport
 */
class SalesReportExport implements FromArray, WithHeadings, ShouldAutoSize
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
            $r[] = make8digits($item->id);
            $r[] = custom_date($item->date);
            $r[] = $this->customerName($item);
            $r[] = $item->tax_amount;
            $r[] = $item->discount_amount;
            $r[] = $item->total;
            $r[] = $item->total - $item->total_paid;

            $data[] = $r;

            $sl++;
        }

        return $data;
    }

    /**
     * customerName
     *
     * @param  mixed $item
     * @return void
     */
    public function customerName($item)
    {
        if ($item->customer_id) {
            return ucfirst($item->customer['full_name'] ?? '');
        } else {
            return ucfirst($item->customer['full_name'] ?? 'Walk-In Customer');
        }
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
            __('custom.customer'),
            __('custom.tax'),
            __('custom.discount'),
            __('custom.total'),
            __('custom.due')
        ];
    }
}
