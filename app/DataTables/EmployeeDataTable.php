<?php

namespace App\DataTables;

use PDF;
use App\Models\Customer;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

/**
 * CustomerDataTable
 */
class EmployeeDataTable extends DataTable
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

                $buttons .= '<a class="dropdown-item" href="' . route('customer.employee.show', $item->id) . '" title="Show"><i class="fa fa-eye"></i> ' . __('custom.show') . ' </a>';


                $buttons .= '<a class="dropdown-item" href="' . route('customer.employee.edit', $item->id) . '" title="Edit"><i class="fa fa-user-edit"></i> ' . __('custom.edit') . ' </a>';


                if ($item->is_verified == Customer::STATUS_UNVERIFIED) {
                    $buttons .= '<a class="dropdown-item" href="' . route('customer.employee.verify', $item->id) . '" title="Edit"><i class="fa fa-user-check"></i> ' . __('custom.verify') . ' </a>';
                } else {
                    $buttons .= '<a class="dropdown-item" href="' . route('customer.employee.verify', $item->id) . '" title="Edit"><i class="fa fa-user-slash"></i> ' . __('custom.unverified') . ' </a>';
                }



                $buttons .= '<form action="' . route('customer.employee.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="dropdown-item text-danger delete-list-data" data-from-name="' . $item->full_name . '" data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
                    ';

                return '<div class="dropdown btn-group dropup">
                <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
                    <div class="dropdown-menu">
                    ' . $buttons . '
                    </div>
                </div>';
            })->editColumn('avatar', function ($item) {
                    return '<img class="img-64" src="' . getStorageImage(Customer::FILE_STORE_PATH, $item->avatar) . '" alt="' . $item->name . '" />';
            })->editColumn('status', function ($item) {
                $badge = $item->status == Customer::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                $data = '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
                if ($item->is_default) {
                    $data .= '<br><small class="text-info">Default</small>';
                }
                return $data;
            })->editColumn('is_verified', function ($item) {
                $badge = $item->is_verified == Customer::STATUS_VERIFIED ? "badge-success" : "badge-danger";
                $text = $item->is_verified == Customer::STATUS_VERIFIED ? "yes" : "no";
                $data = '<span class="badge ' . $badge . '">' . Str::upper($text) . '</span>';

                return $data;
            })->rawColumns(['status', 'is_verified', 'avatar', 'action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Customer $model)
    {
        return $model->where('customer_id', Auth::guard('customer')->user()->id)->where('type', Customer::TYPER_EMPLOYEE)->newQuery();
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
            Column::make('avatar', 'avatar')->title(__('custom.avatar')),
            Column::make('first_name', 'first_name')->title(__('custom.first_name')),
            Column::make('last_name', 'last_name')->title(__('custom.last_name')),
            Column::make('email', 'email')->title(__('custom.email')),
            Column::make('phone', 'phone')->title(__('custom.phone')),
            Column::make('status', 'status')->title(__('custom.status')),
            Column::make('is_verified', 'is_verified')->title(__('custom.is_verified')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Customer_' . date('YmdHis');
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
