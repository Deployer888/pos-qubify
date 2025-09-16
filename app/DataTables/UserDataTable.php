<?php

namespace App\DataTables;

use PDF;
use App\Models\User;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * UserDataTable
 */
class UserDataTable extends DataTable
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
            ->filterColumn('role', function ($query, $keyword) {
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Edit User')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.users.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i>' . __('custom.edit') . '</a>';
                }
                if (auth()->user()->can('Delete User') && auth()->id() != $item->id) {
                    $buttons .= '<form action="' . route('admin.users.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data"  data-from-name="'. $item->name.'" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
';
                }

                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('role', function ($item) {
                return implode(", ", $item->roles->pluck('name')->toArray()) ?? '';
            })->editColumn('avatar', function ($item) {
                return '<img class="img-64" src="' . getStorageImage(User::FILE_STORE_PATH, $item->avatar) . '" alt="' . $item->name . '" />';
            })->editColumn('status', function ($item) {
                $badge = $item->status == User::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })->rawColumns(['avatar', 'status', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()
                     ->where('email', '<>', 'clanadmin@app.com');
                     /*->where('email', '<>', 'admin@app.com');*/
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
            ->addAction(['width' => '55px', 'class' => 'text-center', 'printable' => false, 'exportable' => false, 'title' => 'ACTION'])
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
            Column::make('avatar', 'avatar')->title(__('custom.avatar')),
            Column::make('name', 'name')->title(__('custom.name')),
            Column::make('email', 'email')->title(__('custom.email')),
            Column::make('phone', 'phone')->title(__('custom.phone')),
            Column::make('role', 'role')->title(__('custom.role')),
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
        return 'User_' . date('YmdHis');
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
