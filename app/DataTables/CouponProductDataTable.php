<?php

namespace App\DataTables;

use App\Models\CouponProduct;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CouponProductDataTable extends DataTable
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
                if (auth()->user()->can('Delete Coupon')) {
                    $buttons .= '<form action="' . route('admin.coupon.product.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="hidden" name="_method" value="DELETE">
            <button class="dropdown-item text-danger delete-list-data" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
            ';
                }
                return '<div class="dropdown btn-group dropup">
                  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu">
                  ' . $buttons . '
                  </div>
                </div>';
            })
            ->editColumn('product.name', function ($item) {
                return $item->product->name;
            })->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CouponProduct $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CouponProduct $model)
    {

        return $model->newQuery()->with('product')->where('coupon_id',request('id'))->select('coupon_products.*');
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
            Column::make('product.name', 'product.name')->title(__('custom.product')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Coupon_products_' . date('YmdHis');
    }
    public function pdf()
    {
        $data = $this->getDataForExport();

        $pdf = PDF::loadView('vendor.datatables.print', [
            'data' => $data
        ]);
        return $pdf->download($this->getFilename() . '.pdf');
    }
}
