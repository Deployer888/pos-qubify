<?php

namespace App\DataTables;

use PDF;
use App\Models\Brand;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;


/**
 * BrandDataTable
 */
class BrandDataTable extends DataTable
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
                if (auth()->user()->can('Edit Brand')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.brands.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit')  . ' </a>';
                }
                if (auth()->user()->can('Delete Brand')) {
                    $buttons .= '<form action="' . route('admin.brands.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="hidden" name="_method" value="DELETE">
            <button class="dropdown-item text-danger delete-list-data" data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete')  . '</button></form>
            ';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('image', function ($item) {
                return '<img class="img-64" src="' . getStorageImage(Brand::FILE_STORE_PATH, $item->image) . '" alt="' . $item->name . '" />';
            })->editColumn('status', function ($item) {
                $badge = $item->status == Brand::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                $data = '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
                return $data;
            })->rawColumns(['status', 'image', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Brand $model)
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
            Column::make('image', 'image')->title(__('custom.image')),
            Column::make('name', 'name')->title(__('custom.brand_name')),
            Column::make('desc', 'desc')->title(__('custom.desc')),
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
        return 'Brand_' . date('YmdHis');
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

    protected function getBuilderParameters(): array
    {
        $params = parent::getBuilderParameters();
        $params['buttons'] = [
            [
                'extend' => 'create',
                'text' => '<i class="fa fa-plus"></i> ' . 'Create',
            ],
            [
                'text' => '<i class="fa fa-upload"></i> ' . 'Import',
                'className' => 'import_btn',
                'action' => 'function(e, dt, node, config) { $("#importModal").modal("show"); }',
            ],
            [ 'extend' => 'csv', 'text' => '<i class="fa fa-file-excel"></i> CSV' ],
            [ 'extend' => 'excel', 'text' => '<i class="fa fa-file-excel"></i> Excel' ],
            [ 'extend' => 'pdf', 'text' => '<i class="fa fa-file-pdf"></i> PDF' ],
            [ 'extend' => 'print', 'text' => '<i class="fa fa-print"></i> Print' ],
            [ 'extend' => 'reload', 'text' => '<i class="fa fa-sync"></i> Reload' ],
        ];
        return $params;
    }
}
