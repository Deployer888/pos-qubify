<?php

namespace App\DataTables;

use PDF;
use App\Models\SaleReturn;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * SaleReturnDataTable
 */
class SaleReturnDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->filterColumn('sale_return_items', function ($query, $keyword) {
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Show Sale Return')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.sales-return.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })
            ->editColumn('invoice_id', function ($item) {
                return '<a class="btn btn-link" href="' . route('admin.invoices.show', $item->invoice_id) . '" title="' . __t('invoice') . '">'. make8digits($item->invoice_id).'</a>';
            })
            ->editColumn('date', function ($item) {
                return date('Y-m-d', strtotime($item->return_date));
            })
            ->editColumn('sale_return_items', function ($item) {
                return $item->saleReturnItems->count();
            })
            ->editColumn('invoice.customer', function ($item) {
                if (!empty($item->invoice->customer_id)) {
                    return ucfirst($item->invoice->customer['full_name'] ?? '');
                } else {
                    return ucfirst($item->invoice->customer['full_name'] ?? 'Walk-In Customer');
                }
            })
            ->rawColumns(['invoice_id', 'date', 'sale_return_items', 'action', 'invoice.customer'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SaleReturn $model)
    {
        return $model->with(['invoice', 'invoice.customerInfo', 'saleReturnItems'])->newQuery()->select('sale_returns.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '55px', 'class' => "text-center", 'width' => '55px', 'printable' => false, 'exportable' => false, 'title' => __('custom.action')])
            ->parameters([
                'dom'     => 'Bfrtilp',
                'order'   => [[1, 'desc']],
                'buttons' => [
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    'reload',
                ],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', __('custom.sl')),
            Column::make('invoice_id', 'invoice_id')->title(__t('invoice_number')),
            Column::make('invoice.customer', 'invoice.customer')->title(__t('customer_name')),
            Column::make('return_date', 'return_date')->title(__t('date')),
            Column::make('sale_return_items', 'sale_return_items')->title(__t('total_product'))->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Sales_return_list_' . date('YmdHis');
    }

    /**
     * pdf
     *
     * @return void
     */
    public function pdf()
    {
        $data = $this->getDataForExport();

        $pdf = PDF::loadView('vendor.datatables.print', [
            'data' => $data
        ]);
        return $pdf->download($this->getFilename() . '.pdf');
    }
}
