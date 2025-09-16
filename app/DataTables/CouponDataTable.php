<?php

namespace App\DataTables;

use App\Models\Coupon;
use App\Models\CouponProduct;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
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
                if (auth()->user()->can('Edit Coupon')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.coupons.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit') . ' </a>';
                }
                if (auth()->user()->can('Delete Coupon')) {
                    $buttons .= '<form action="' . route('admin.coupons.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="hidden" name="_method" value="DELETE">
            <button class="dropdown-item text-danger delete-list-data"  data-from-name="'. $item->title.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
            ';
                }
                $buttons .= '<a class="dropdown-item" href="' . route('admin.coupon.products', $item->id) . '" title="Edit"><i class="flaticon-new-product"></i> ' . __('custom.products') . ' </a>';
                return '<div class="dropdown btn-group dropup">
                  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu">
                  ' . $buttons . '
                  </div>
                </div>';
            })
            ->editColumn('banner', function ($item) {
                return '<img class="img-64" src="' . getStorageImage(Coupon::FILE_STORE_PATH, $item->banner) . '" alt="' . $item->name . '" />';
            })->editColumn('status', function ($item) {

                $startDate  = \Carbon\Carbon::parse($item->start_date)->format('Y-m-d');
                $endDate    = \Carbon\Carbon::parse($item->end_date)->format('Y-m-d');
//                $check      = \Carbon\Carbon::now()->between($startDate,$endDate);
                if($startDate <= \Carbon\Carbon::now()->format('Y-m-d') && $endDate >= \Carbon\Carbon::now()->format('Y-m-d')){
                    $badge      = $item->status == Coupon::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                    $data       = '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
                    return $data;
                }else{
                    $data       = '<span class="badge badge-danger">' . Str::upper(Coupon::STATUS_INACTIVE) . '</span>';
                    return $data;
                }


            })
            ->editColumn('validity', function ($item) {

                $startDate  = \Carbon\Carbon::parse($item->start_date)->format('Y-m-d');
                $endDate    = \Carbon\Carbon::parse($item->end_date)->format('Y-m-d');
//                $check      = \Carbon\Carbon::now()->between($startDate,$endDate);
                if($startDate <= \Carbon\Carbon::now()->format('Y-m-d') && $endDate >= \Carbon\Carbon::now()->format('Y-m-d')){
                    $data   = '<p class="text-success">' . Carbon::parse($item->start_date)->format('d M Y') . ' - ' . Carbon::parse($item->end_date)->format('d M Y') .'</p>';
                }else {
                    $data   = '<p class="text-danger">' . Carbon::parse($item->start_date)->format('d M Y') . ' - ' . Carbon::parse($item->end_date)->format('d M Y') .'</p>';
                }
                return $data;
            })
            ->editColumn('discount', function ($item) {
                return $item->discount .' '. ($item->discount_type == 'percent' ? '%' : currencySymbol());
            })
            ->rawColumns(['validity','status', 'banner', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Coupon $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Coupon $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['order']    = [[2, 'asc']];

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
            Column::make('banner', 'banner')->title(__('custom.banner')),
            Column::make('title', 'title')->title(__('custom.title')),
            Column::make('code', 'code')->title(__('custom.coupon_code')),
            Column::make('discount', 'discount')->title(__('custom.discount')),
            Column::make('validity', 'validity')->title(__('custom.validity')),
            Column::make('minimum_shopping', 'minimum_shopping')->title(__('custom.minimum_quantity')),
            Column::make('status', 'status')->title(__('custom.status')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Coupons_' . date('YmdHis');
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
