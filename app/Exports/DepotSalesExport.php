<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * DepotSalesExport
 * 
 * Excel export class for depot sales/invoices data.
 * Implements Laravel Excel interfaces for professional Excel output with proper formatting.
 * 
 * Requirements covered: 1.1, 1.2, 1.3, 6.1, 6.2, 6.3
 */
class DepotSalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $sales;

    /**
     * Constructor
     * 
     * @param \Illuminate\Support\Collection $sales Collection of DepotSale models with loaded relationships
     */
    public function __construct($sales)
    {
        // Store the sales collection - relationships should be loaded before passing to constructor
        // Expected relationships: depot.user, customer, items
        $this->sales = $sales;
    }

    /**
     * Return the collection of sales data for Excel export
     * 
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->sales;
    }

    /**
     * Define the headings for the Excel export
     * 
     * @return array Column headers for the Excel file
     */
    public function headings(): array
    {
        return [
            'Invoice No',
            'Date',
            'Time',
            'Depot Type',
            'Depot City',
            'Depot Manager',
            'Customer Name',
            'Customer Mobile',
            'Customer Family ID',
            'Items Count',
            'Subtotal',
            'Tax',
            'Total Amount'
        ];
    }

    /**
     * Map each sale record to the export format
     * 
     * @param \App\Models\DepotSale $sale The sale record to map
     * @return array Mapped data array for Excel row
     */
    public function map($sale): array
    {
        return [
            $sale->invoice_no,
            $sale->created_at->format('Y-m-d'),
            $sale->created_at->format('H:i:s'),
            $sale->depot->depot_type ?? '',
            $sale->depot->city ?? '',
            $sale->depot->user->name ?? '',
            $sale->customer->name ?? 'Walk-in Customer',
            $sale->customer->mobile ?? '',
            $sale->customer->family_id ?? '',
            $sale->items->sum('quantity'),
            number_format($sale->subtotal, 2),
            number_format($sale->tax, 2),
            number_format($sale->total, 2)
        ];
    }

    /**
     * Apply styles to the worksheet for professional formatting
     * 
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet The worksheet to style
     * @return array Style configuration array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE0E0E0',
                    ],
                ],
            ],
            // Style for amount columns (align right)
            'K:M' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
                ],
            ],
            // Style for items count column (align center)
            'J' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}