<?php

namespace App\DataTables;

use PDF;
use App\Models\Purchase;
use Illuminate\Support\Str;
use App\Models\PurchaseReturn;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * PurchaseReturnDataTable
 */
class PurchaseReturnDataTable extends DataTable
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
            ->filterColumn('purchase_item_return', function ($query, $keyword) {
            })
            ->addColumn('action', function ($item) {

                $buttons = '';
                $buttons .= '<a class="dropdown-item" href="' . route('admin.purchases.return.show', $item->id) . '" title="' . __t('show') . '"><i class="fa fa-eye"></i> ' . __t('show') . ' </a>';
                $buttons .= '<form action="' . route('admin.purchases.return.delete', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data"  data-from-name="'.'No-'. $item->purchase->purchase_number.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="fa fa-trash"></i> ' . __('custom.delete') . '</button></form>
';

                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })
            ->editColumn('purchase.purchase_number', function ($item) {
                return '<a class="btn btn-link" href="'.route('admin.purchases.show', $item->purchase_id).'">'.$item->purchase->purchase_number.'</a>';
            })
            ->editColumn('date', function ($item) {
                return date('Y-m-d', strtotime($item->return_date));
            })
            ->editColumn('status', function ($item) {
                $badge = $item->status == Purchase::STATUS_REQUESTED ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })
            ->editColumn('purchase_item_return', function ($item) {
                return $item->purchaseReturnItems->count();
            })
            ->editColumn('purchase.supplier.first_name', function ($item) {
                return $item->purchase->supplier->full_name;
            })
            ->editColumn('total', function ($item) {
                return currencySymbol() . make2decimal($item->total);
            })
            ->rawColumns(['status', 'received', 'date', 'purchase_item_return', 'action', 'purchase.supplier.first_name', 'purchase.purchase_number'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PurchaseReturn $model)
    {
        return $model->with(['purchase.supplier', 'purchase.warehouse', 'purchaseReturnItems'])->newQuery()->select('purchase_returns.*');
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
            Column::make('return_date', 'return_date')->title(__t('date')),
            Column::make('total', 'total')->title(__t('total')),
            Column::make('purchase_item_return', 'purchase_item_return')->title(__t('total_product'))->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Purchase_return_list_' . date('YmdHis');
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
