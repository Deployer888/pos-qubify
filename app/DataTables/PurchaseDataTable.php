<?php

namespace App\DataTables;

use PDF;
use App\Models\Purchase;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;


/**
 * PurchaseDataTable
 */
class PurchaseDataTable extends DataTable
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
            ->filterColumn('missing_item', function ($query, $keyword) {
            })
            ->filterColumn('purchaseItems', function ($query, $keyword) {
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Show Purchase')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                }
                if ($item->status != Purchase::STATUS_CANCEL) {
                    if ($item->received == null) {
                        if (auth()->user()->can('Edit Purchase')) {
                            $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.edit', $item->id) . '" title="' . __t('edit') . '"><i class="fa fa-edit"></i> ' . __('custom.edit') . ' </a>';
                        }
                        if (auth()->user()->can('Cancel Purchase')) {
                            $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.cancel', $item->id) . '" title="' . __t('cancel') . '"><i class="fa fa-times"></i> ' . __('custom.cancel') . ' </a>';
                        }
                    }
                    if (auth()->user()->can('Receive Purchase')) {
                        $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.receive', $item->id) . '" title="' . __t('receive') . '"><i class="fa fa-arrow-circle-down"></i> ' . __t('receive') . ' </a>';
                    }
                    if ($item->status == Purchase::STATUS_CONFIRMED) {

                        if ($item->received) {
                            if (auth()->user()->can('Return Purchase')) {
                                $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.return', $item->id) . '" title="' . __t('cancel') . '"><i class="fa fa-undo-alt"></i> ' . __t('return') . ' </a>';
                            }
                        }
                    }
//                    if ($item->status != Purchase::STATUS_CONFIRMED) {
//                        if (auth()->user()->can('Confirm Purchase')) {
//                            $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.confirm', $item->id) . '" title="' . __t('confirm') . '"><i class="fa fa-check-square"></i> ' . __t('confirm') . ' </a>';
//                        }
//                    }
                }

                if (auth()->user()->can('Show Purchase')) {
                    $buttons .= '<form action="' . route('admin.purchases.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data" data-from-name="'.'No-'. $item->purchase_number.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="fa fa-trash"></i> ' . __('custom.delete') . '</button></form>
';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })
            ->editColumn('date', function ($item) {
                return date('Y-m-d', strtotime($item->date));
            })
            ->editColumn('purchase_number', function ($item) {
                return '<a class="btn btn-link" href="' . route('admin.purchases.show', $item->id) . '">' . $item->purchase_number . '</a>';
            })
//            ->editColumn('status', function ($item) {
//                $badge = $item->status == Purchase::STATUS_REQUESTED ? "badge-success" : ($item->status == Purchase::STATUS_CONFIRMED ? "badge-primary" : "badge-danger");
//                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
//            })
            ->editColumn('received', function ($item) {

                if ($item->received) {
                    return '<span class="badge badge-success">' . __t('received') . '</span>';

                } else {
                    if ($item->status == \App\Models\Purchase::STATUS_CANCEL) {
                        return '<span class="badge badge-danger">' . ucfirst(\App\Models\Purchase::STATUS_CANCEL) . '</span>';
                    } elseif ($item->status == \App\Models\Purchase::STATUS_REQUESTED) {
                        return '<span class="badge badge-info">' . ucfirst(\App\Models\Purchase::STATUS_REQUESTED) . '</span>';
                    } elseif ($item->status == \App\Models\Purchase::STATUS_CONFIRMED) {
                        return '<span class="badge badge-success">' . ucfirst(\App\Models\Purchase::STATUS_CONFIRMED) . '</span>';
                    } else {
                        return '<span class="badge badge-warning">' . __t('not_received_yet') . '</span>';
                    }
                }

            })
            ->editColumn('purchaseItems', function ($item) {
                return $item->purchaseItems->count();
            })
            ->editColumn('missing_item', function ($item) {
                if ($item->received) {
                    $purchaseItemQty = $item->purchaseItems->sum('quantity');
                    $purchaseReceiveItemQty = 0;
                    foreach ($item->purchaseItems as $purchaseItems) {
                        $purchaseReceiveItemQty += $purchaseItems->receiveItems->sum('quantity');
                    }
                    if ($purchaseItemQty != $purchaseReceiveItemQty) {
                        return '<span class="badge badge-danger">' . __t('missing') .' ('.$purchaseItemQty - $purchaseReceiveItemQty.')' .'</span>';
                    }
                }
            })
            ->editColumn('total', function ($item) {
                return currencySymbol() . make2decimal($item->total);
            })
            ->editColumn('supplier.first_name', function ($item) {
                return $item->supplier->full_name;
            })
            ->rawColumns(['status', 'received', 'date', 'purchaseItems', 'action', 'missing_item', 'supplier.first_name', 'purchase_number'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Purchase $model)
    {
        return $model->with(['supplier', 'warehouse', 'purchaseItems'])->newQuery()->select('purchases.*');
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
            ->addAction(['width' => '55px', 'class' => "text-center", 'width' => '55px', 'printable' => false, 'exportable' => false, 'title' => __('custom.action')])
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
            Column::make('purchase_number', 'purchase_number')->title(__t('purchase_number')),
            Column::make('supplier.first_name', 'supplier.first_name')->title(__t('supplier_name')),
            Column::make('warehouse.name', 'warehouse.name')->title(__t('warehouse')),
            Column::make('date', 'date')->title(__t('date')),
            Column::make('total', 'total')->title(__t('total')),
//            Column::make('status', 'status')->title(__('custom.status')),
            Column::make('purchaseItems', 'purchaseItems')->title(__t('total_product'))->addClass('text-center'),
            Column::make('received', 'received')->title(__t('received')),
            Column::make('missing_item', 'missing_item')->title(__t('missing_item')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Purchase_' . date('YmdHis');
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
