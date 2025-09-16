<?php

namespace App\DataTables;

use PDF;
use App\Models\Invoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * InvoiceDataTable
 */
class InvoiceDataTable extends DataTable
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
                if (auth()->user()->can('Show Invoice')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.show', $item->id) . '" title="Show"><i class="mdi mdi mdi-desktop-mac"></i> ' . __('custom.show') . ' </a>';
                }
                if (auth()->user()->can('Edit Invoice')) {
                    if ($item->status == Invoice::STATUS_PENDING) {
                        $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit') . ' </a>';
                    }
                }
                if ($item->status != Invoice::STATUS_PENDING && auth()->user()->can('Return Invoice')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.sales-return.create', $item->id) . '" title="Sales Return"><i class="mdi mdi-undo"></i> ' . __('custom.return') . ' </a>';
                }
                if (auth()->user()->can('View Payment Invoice')) {
                    $buttons .= '<a class="dropdown-item view-invoice-payment" data-invoice-id="' . $item->id . '" href="#" title="View Payment"><i class="mdi mdi-cash-multiple"></i> ' . __('custom.view_payment') . ' </a>';
                }
                if ($item->status != Invoice::STATUS_PAID && auth()->user()->can('Make Payment Invoice')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.makePayment', $item->id) . '" title="Make Payment"><i class="mdi mdi-cash"></i> ' . __('custom.make_payment') . ' </a>';
                }
                if (auth()->user()->can('Download Invoice')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.download', $item->id) . '" title="Download"><i class="mdi mdi-briefcase-download-outline"></i> ' . __('custom.download') . ' </a>';
                }
//                if (auth()->user()->can('delivery Invoice')) {
                if ($item->delivery_status != Invoice::DELIVERY_STATUS_DELIVERED &&  ($item->status == Invoice::STATUS_PAID || $item->status == Invoice::STATUS_PARTIALLY_PAID)) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.delivery.status.change', ['id' => $item->id, 'status' => Invoice::DELIVERY_STATUS_DELIVERED]) . '" title="Download"><i class="mdi mdi-truck-delivery"></i> ' . __('custom.make_delivered') . ' </a>';
                }
                if ($item->delivery_status != Invoice::DELIVERY_STATUS_CANCELED ) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.invoices.delivery.status.change', ['id' => $item->id, 'status' => Invoice::DELIVERY_STATUS_CANCELED]) . '" title="Download"><i class="mdi mdi-cancel"></i> ' . __('custom.make_cancel') . ' </a>';
                }
//                }
                if (auth()->user()->can('Send Invoice')) {
                    $buttons .= '<a class="dropdown-item send-invoice-payment" data-invoice-id="' . $item->id . '" href="#" title="Send"><i class="mdi mdi-email-outline"></i> ' . __('custom.send') . ' </a>';
                }
                if (auth()->user()->can('Link Invoice')) {
                    $buttons .= '<a class="dropdown-item live-invoice-payment" data-invoice-token="' . $item->token . '" href="#" title="Live Link"><i class="mdi mdi-web"></i> ' . __('custom.link') . ' </a>';
                }
                if (auth()->user()->can('Delete Invoice')) {
//                    if ($item->status == Invoice::STATUS_PENDING) {
                    $buttons .= '<form action="' . route('admin.invoices.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data"  data-from-name="'.'inv-'. make8digits($item->id).'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
';
//                    }
                }

                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('id', function ($item) {
                return '<a class="btn btn-link" href="' . route('admin.invoices.show', $item->id) . '" title="Show">'.make8digits($item->id).' </a>';
            })->editColumn('customer', function ($item) {
                if ($item->customer_id) {
                    return ucfirst($item->customer['full_name'] ?? '');
                } else {
                    return ucfirst($item->customer['full_name'] ?? 'Walk-In Customer');
                }
            })->editColumn('warehouse', function ($item) {
                if ($item->warehouse_id){
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

            ->rawColumns(['status','delivery_status', 'action', 'id', 'warehouse'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model)
    {
        return $model->newQuery()->with('warehouse')->select('invoices.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['order']    = [[1, 'desc']];

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
