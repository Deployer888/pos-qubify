<?php

namespace App\DataTables;

use App\Models\SaleReturnRequest;
use PDF;
use App\Models\SaleReturn;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * SaleReturnDataTable
 */
class SaleReturnRequestDataTable extends DataTable
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
            ->filterColumn('sale_return_items', function ($query, $keyword) {})
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->guard('customer')->check()) {
                    $buttons .= '<a class="dropdown-item" href="' . route('customer.products-return-request.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                } else {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.products-return-request.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                    if ($item->status == SaleReturnRequest::STATUS_PENDING) {
                        $buttons .= '<a class="dropdown-item" href="' . route('admin.products-return-request.accept', $item->id) . '" title="' . __t('accept') . '"><i class="fa fa-check"></i> ' . __t('accept') . ' </a>';
                        $buttons .= '<a class="dropdown-item" href="' . route('admin.products-return-request.reject', $item->id) . '" title="' . __t('reject') . '"><i class="fa fa-times"></i> ' . __t('reject') . ' </a>';
                    } elseif ($item->status == SaleReturnRequest::STATUS_REJECTED) {
                        $buttons .= '<a class="dropdown-item" href="' . route('admin.products-return-request.accept', $item->id) . '" title="' . __t('accept') . '"><i class="fa fa-check"></i> ' . __t('accept') . ' </a>';
                    } elseif ($item->status == SaleReturnRequest::STATUS_ACCEPTED) {
                        //                        $buttons .= '<a class="dropdown-item" href="' . route('admin.products-return-request.reject', $item->id) . '" title="' . __t('reject') . '"><i class="fa fa-times"></i> ' . __t('reject') . ' </a>';
                    }
                }


                return '<div class="dropdown btn-group dropup">
                  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu">
                  ' . $buttons . '
                  </div>
                </div>';
            })
            ->editColumn('invoice_id', function ($item) {
                if (auth()->guard('customer')->check()) {
                    $route = route('customer.invoices.show', $item->invoice_id);
                } else {
                    $route = route('admin.invoices.show', $item->invoice_id);
                }
                return '<a class="btn btn-link" href="' . $route . '" title="' . __t('invoice') . '">' . make8digits($item->invoice_id) . '</a>';
            })
            ->editColumn('date', function ($item) {
                return date('Y-m-d', strtotime($item->return_date));
            })
            ->editColumn('sale_return_items', function ($item) {
                return $item->saleReturnRequestItems->count();
                //                return $item->saleReturnRequestItems->count();
            })
            ->editColumn('invoice.customer', function ($item) {
                if (!empty($item->invoice->customer_id)) {
                    return ucfirst($item->invoice->customer['full_name'] ?? '');
                } else {
                    return ucfirst($item->invoice->customer['full_name'] ?? 'Walk-In Customer');
                }
            })
            ->editColumn('status', function ($item) {
                return returnRequestStatusBadge($item->status);
            })

            ->editColumn('invoice.delivery_status', function ($item) {
                return invoiceDeliveryStatusBadge(optional($item->invoice)->delivery_status);
            })
            ->editColumn('warehouse.name', function ($item) {
                return $item->warehouse_id ? $item->warehouse->name : '';
            })
            ->rawColumns(['status', 'invoice.delivery_status', 'invoice_id', 'date', 'sale_return_items', 'action', 'invoice.customer'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SaleReturnRequest $model)
    {
        return $model->with(['invoice.customerInfo', 'saleReturnRequestItems', 'requestedBy', 'warehouse'])
            //            ->orderBy('id', 'DESC')
            ->when(auth()->guard('customer')->check(), function ($query) {
                $query->whereHas('requestedBy', function ($q) {
                    $q->where('requested_by', user_id());
                });
            })
            ->newQuery()->select('sale_return_requests.*');
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
                'order'   => [[1, 'asc']],
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
            Column::make('warehouse.name', 'warehouse.name')->title(__t('return_warehouse')),
            Column::make('return_date', 'return_date')->title(__t('date')),
            Column::make('sale_return_items', 'sale_return_items')->title(__t('total_product'))->addClass('text-center'),
            Column::make('status', 'status')->title(__t('status'))->addClass('text-center'),
            Column::make('invoice.delivery_status', 'invoice.delivery_status')->title(__('custom.delivery_status')),
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
