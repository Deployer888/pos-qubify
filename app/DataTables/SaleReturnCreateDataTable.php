<?php

namespace App\DataTables;

use PDF;
use App\Models\Invoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * SaleReturnCreateDataTable
 */
class SaleReturnCreateDataTable extends DataTable
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
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Show Sale Return')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.show', $item->id) . '" title="Show"><i class="mdi mdi mdi-desktop-mac"></i> ' . __('custom.show') . ' </a>';
                }
                if (auth()->user()->can('Return Sale Return')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.sales-return.create', $item->id) . '" title="Sales Return"><i class="mdi mdi-undo"></i> ' . __('custom.return') . ' </a>';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('id', function ($item) {
                return '<a class="btn btn-link" href="' . route('admin.invoices.show', $item->id) . '" title="' . __t('invoice') . '">'. make8digits($item->id).'</a>';
            })->editColumn('customer', function ($item) {
                if ($item->customer_id) {
                    return ucfirst($item->customer['full_name'] ?? '');
                } else {
                    return ucfirst($item->customer['full_name'] ?? 'Walk-In Customer');
                }
            })->editColumn('warehouse', function ($item) {
                return optional($item->warehouse)->name;
            })->editColumn('date', function ($item) {
                return custom_date($item->date);
            })->editColumn('payment_type', function ($item) {
                return strtoupper($item->payment_type);
            })->editColumn('due_date', function ($item) {
                return custom_date($item->date);
            })->editColumn('status', function ($item) {
                return invoiceStatusBadge($item->status);
            })
            ->editColumn('delivery_status', function ($item) {
                return invoiceDeliveryStatusBadge($item->delivery_status);
            })
            ->editColumn('total', function ($item) {
                return currencySymbol() . make2decimal($item->total);
            })
            ->editColumn('total_paid', function ($item) {
                return currencySymbol() . make2decimal($item->total_paid);
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->orWhere('id', 'like', '%' . ltrim($keyword, '0') . '%');
            })
            ->rawColumns(['status','delivery_status', 'action', 'id'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        return $model->newQuery()->with('warehouse')
//            ->orderBy('id', 'DESC')
            ->where(function ($query){
                $query->where('status', 'paid')
                    ->orWhere('status', 'partially_paid');
            })
            ->select('invoices.*');
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
            ->addAction(['width' => '55px', 'class' => "text-center", 'printable' => false, 'exportable' => false, 'title' => 'ACTION'])
            ->parameters([
                'dom' => 'Bfrtilp',
                'order' => [[1, 'desc']],
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
            Column::make('id', 'id')->title(__('custom.invoice_id')),
            Column::make('warehouse', 'warehouse.name')->title(__('custom.warehouse')),
            Column::make('date', 'date')->title(__('custom.date')),
            Column::make('customer', 'customer')->title(__('custom.customer')),
            Column::make('total', 'total')->title(__('custom.total')),
            Column::make('total_paid', 'total_paid')->title(__('custom.total_paid')),
            Column::make('payment_type', 'payment_type')->title(__('custom.payment_type')),
            Column::make('status', 'status')->title(__('custom.status')),
            Column::make('delivery_status', 'delivery_status')->title(__('custom.delivery_status')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Invoice_' . date('YmdHis');
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
