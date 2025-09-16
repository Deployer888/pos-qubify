<?php

namespace App\DataTables;

use PDF;
use App\Models\Invoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * InvoiceDataTable
 */
class CustomerInvoiceDataTable extends DataTable
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
                $buttons .= '<a class="dropdown-item" href="' . route('customer.invoices.show', $item->id) . '" title="Show"><i class="mdi mdi mdi-desktop-mac"></i> ' . __('custom.show') . ' </a>';
                if ($item->status != Invoice::STATUS_PENDING) {
                    $buttons .= '<a class="dropdown-item" href="' . route('customer.products-return-request.create', $item->id) . '" title="Sales Return"><i class="mdi mdi-undo"></i> ' . __('custom.return') . ' </a>';
                }

                $buttons .= '<a class="dropdown-item view-customer-invoice-payment" data-invoice-id="' . $item->id . '" href="#" title="View Payment"><i class="mdi mdi-cash-multiple"></i> ' . __('custom.view_payment') . ' </a>';

                $buttons .= '<a class="dropdown-item" href="' . route('customer.invoices.download', $item->id) . '" title="Download"><i class="mdi mdi-briefcase-download-outline"></i> ' . __('custom.download') . ' </a>';
                $buttons .= '<a class="dropdown-item live-invoice-payment" data-invoice-token="' . $item->token . '" href="#" title="Live Link"><i class="mdi mdi-web"></i> ' . __('custom.link') . ' </a>';

                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('id', function ($item) {
                return '<a class="btn btn-link" href="' . route('customer.invoices.show', $item->id) . '" title="Show">' . make8digits($item->id) . ' </a>';
            })->editColumn('customer', function ($item) {
                if ($item->customer_id) {
                    return ucfirst($item->customer['full_name'] ?? '');
                } else {
                    return ucfirst($item->customer['full_name'] ?? 'Walk-In Customer');
                }
            })->editColumn('warehouse', function ($item) {
                if ($item->warehouse_id) {
                    return optional($item->warehouse)->name;
                }
            })->editColumn('date', function ($item) {
                return custom_date($item->date);
            })->editColumn('payment_type', function ($item) {
                return strtoupper($item->payment_type);
            })->editColumn('due_date', function ($item) {
                return custom_date($item->date);
            })->editColumn('total', function ($item) {
                return currencySymbol() . $item->total;
            })
            ->editColumn('total_paid', function ($item) {
                return currencySymbol() . $item->total_paid;
            })
            ->editColumn('status', function ($item) {
                return invoiceStatusBadge($item->status);
            })
            ->editColumn('delivery_status', function ($item) {
                return invoiceDeliveryStatusBadge($item->delivery_status);
            })
            ->filterColumn('id', function ($query, $keyword) {
                $query->orWhere('id', 'like', '%' . ltrim($keyword, '0') . '%');
            })

            ->rawColumns(['status', 'delivery_status', 'action', 'id', 'warehouse'])->addIndexColumn();
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
            ->where('customer_id', user_id())
            ->select('invoices.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['order']    = [[1, 'asc']];
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '55px', 'class' => "text-center", 'printable' => false, 'exportable' => false, 'title' => 'ACTION'])
            ->parameters($params);
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
     * filename
     *
     * @return void
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
