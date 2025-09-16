<?php

namespace App\DataTables;

use PDF;
use App\Models\Purchase;
use Illuminate\Support\Str;
use App\Models\PurchaseReceive;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * PurchaseReceiveDataTable
 */
class PurchaseReceiveDataTable extends DataTable
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
            ->filterColumn('purchaseItemReceives', function ($query, $keyword) {
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.receive.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                $buttons .= '<form action="' . route('admin.purchases.receive.delete', $item->id) . '"  id="delete-form-' . $item->id . '" method="post" >
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data" data-from-name="'.'No-'. $item->purchase->purchase_number.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="fa fa-trash"></i> ' . __('custom.delete') . '</button></form>
';

                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })
            ->editColumn('purchase.purchase_number', function ($item) {
                return '<a class="btn btn-link" href="'.route('admin.purchases.show', $item->id).'">'.$item->purchase->purchase_number.'</a>';
            })
            ->editColumn('date', function ($item) {
                return date('Y-m-d', strtotime($item->date));
            })
            ->editColumn('status', function ($item) {
                $badge = $item->status == Purchase::STATUS_REQUESTED ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })
            ->editColumn('received', function ($item) {
                if ($item->received) {
                    return '<span class="badge badge-warning">' . __t('received') . '</span>';
                } else {
                    return '<span class="badge badge-success">' . __t('not_received_yet') . '</span>';
                }
            })
            ->editColumn('purchaseItemReceives', function ($item) {
                return $item->purchaseItemReceives->count();
            })
            ->editColumn('purchase.supplier.first_name', function ($item) {
                return $item->purchase->supplier->full_name;
            })
            ->editColumn('total', function ($item) {
                return currencySymbol() . make2decimal($item->total);
            })
            ->rawColumns(['status', 'received', 'date', 'purchaseItemReceives', 'action', 'purchase.supplier.first_name', 'total', 'purchase.purchase_number'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseReceive $model)
    {
        return $model->newQuery()
            ->with(['purchase.supplier', 'purchase.warehouse', 'purchaseItemReceives'])
            ->whereHas('purchase') // Only include PurchaseReceive that has a related Purchase
            ->select('purchase_receives.*');
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
            Column::make('purchase.purchase_number', 'purchase.purchase_number')->title(__t('purchase_number')),
            Column::make('purchase.supplier.first_name', 'purchase.supplier.first_name')->title(__t('supplier_name')),
            Column::make('purchase.warehouse.name', 'purchase.warehouse.name')->title(__t('warehouse')),
            Column::make('receive_date', 'receive_date')->title(__t('date')),
            Column::make('total', 'total')->title(__t('total')),
            Column::make('purchaseItemReceives', 'purchaseItemReceives')->title(__t('total_product'))->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Purchase_receive_list_' . date('YmdHis');
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
