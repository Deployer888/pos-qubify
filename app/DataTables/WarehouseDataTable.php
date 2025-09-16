<?php

namespace App\DataTables;

use PDF;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * WarehouseDataTable
 */
class WarehouseDataTable extends DataTable
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
                $buttons .= '<a class="dropdown-item" href="' . route('admin.warehouses.show', $item->id) . '" title="Show"><i class="fa fa-eye"></i> ' . __('custom.show') . ' </a>';
                if (auth()->user()->can('Edit Warehouse')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.warehouses.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit') . ' </a>';
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.warehouses.show-storage-store-and-out', $item->id) . '" title="Show"><i class="fa fa-eye"></i> ' . __('custom.show_storage_history') . ' </a>';
                }
                $buttons .= '<a class="dropdown-item" href="' . route('admin.warehouse.barcode.download', $item->id) . '" title="Download Barcode"><i class="mdi mdi-download"></i> ' . __('custom.download_barcode') . ' </a>';
                if (auth()->user()->can('Delete Warehouse')) {
                    $buttons .= '<form action="' . route('admin.warehouses.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data" data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('status', function ($item) {
                $badge = $item->status == Warehouse::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                $data = '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
                if ($item->is_default) {
                    $data .= '<br><small class="text-info">Default</small>';
                }
                return $data;
            })->editColumn('name', function ($item){
                return '<a class="btn btn-link" title="Show Details" href="'. route('admin.warehouses.show', $item->id) .'">'.$item->name.'</a>';
            })
            ->editColumn('barcode', function ($item) {
                return '<img class="img-64" src="' . getStorageImage(Warehouse::FILE_STORE_PATH, $item->barcode_image) . '" alt="' . $item->barcode_image . '" />';
            })
            ->rawColumns(['status','barcode', 'action', 'name'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Warehouse $model)
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
        $params['order']    = [[7, 'asc']];

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
            Column::make('barcode', 'barcode')->title(__('custom.barcode')),
            Column::make('name', 'name')->title(__('custom.warehouse_name')),
            Column::make('email', 'email')->title(__('custom.email')),
            Column::make('phone', 'phone')->title(__('custom.phone')),
            Column::make('company_name', 'company_name')->title(__('custom.company_name')),
            Column::make('address_1', 'address_1')->title(__('custom.address_1')),
            Column::make('address_2', 'address_2')->title(__('custom.address_2')),
            Column::make('priority', 'priority')->title(__('custom.priority')),
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
        return 'Warehouse_' . date('YmdHis');
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
