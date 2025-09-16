<?php

namespace App\DataTables;

use App\Models\SystemCity;
use App\Models\SystemCountry;
use App\Models\SystemState;
use PDF;
use App\Models\Brand;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;


/**
 * BrandDataTable
 */
class CityDataTable extends DataTable
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
            ->filterColumn('state_id', function ($query, $keyword) {
                $query->whereHas('state', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}");
                });
            })
            ->filterColumn('state.country_id', function ($query, $keyword) {
                $query->whereHas('state.country', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}");
                });
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                $buttons .= '<a class="dropdown-item" href="' . route('admin.cities.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit')  . ' </a>';
                $buttons .= '<form action="' . route('admin.cities.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_method" value="DELETE">
                <button class="dropdown-item text-danger delete-list-data" data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete')  . '</button></form>
                ';

                return '<div class="dropdown btn-group dropup">
                  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                  <div class="dropdown-menu">
                  ' . $buttons . '
                  </div>
                </div>';
            })
            ->addColumn('state_id', function ($item) {
                return optional($item->state)->name;
            })
            ->addColumn('state.country_id', function ($item) {
                return optional(optional($item->state)->country)->name;
            })
            ->rawColumns([ 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param SystemCity $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SystemCity $model)
    {
        return $model->with('state.country')->newQuery();
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
            ->addAction(['width' => '55px', 'class' => "text-center", 'printable' => false, 'exportable' => false, 'title' => __('custom.action')])
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
            Column::make('name', 'name')->title(__('custom.name')),
            Column::make('state_id', 'state_id')->title(__('custom.state')),
            Column::make('state.country_id', 'state.country_id')->title(__('custom.country')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'SystemState' . date('YmdHis');
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
