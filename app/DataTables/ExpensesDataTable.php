<?php

namespace App\DataTables;

use PDF;
use App\Models\Expenses;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * ExpensesDataTable
 */
class ExpensesDataTable extends DataTable
{

    protected array $actions = [
        'createAction',
        'csv',
        'excel',
        'pdf',
        'print',
        'reload',
        ];

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
            ->filterColumn('expenseBy.name', function (){

            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                if (auth()->user()->can('Show Expenses')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.expenses.show', $item->id) . '" title="Show"><i class="mdi mdi mdi-desktop-mac"></i> ' . __('custom.show') . ' </a>';
                }
                if (auth()->user()->can('Edit Expenses')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.expenses.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit') . ' </a>';
                }

                if (auth()->user()->can('Delete Expenses')) {
                    $buttons .= '<form action="' . route('admin.expenses.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post">
<input type="hidden" name="_token" value="' . csrf_token() . '">
<input type="hidden" name="_method" value="DELETE">
<button class="dropdown-item text-danger delete-list-data" data-from-name="'. $item->title.'" data-from-id="' . $item->id . '"  data-from-id="' . $item->id . '"   type="button" title="Delete"><i class="mdi mdi-trash-can-outline"></i> ' . __('custom.delete') . '</button></form>
';
                }
                return '<div class="dropdown btn-group dropup">
  <a href="#" class="btn btn-dark btn-sm" data-toggle="dropdown" data-boundary="viewport"  aria-haspopup="true" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></a>
  <div class="dropdown-menu">
  ' . $buttons . '
  </div>
</div>';
            })->editColumn('category.name', function ($item) {
                return $item->category->name ?? '';
            })
            ->editColumn('date', function ($item) {
                return $item->date;
            })
            ->editColumn('total', function ($item) {
                return currencySymbol() . make2decimal($item->total);
            })->editColumn('notes', function ($item) {
                return Str::limit($item->notes, 50, '...');
            })->editColumn('expenseBy.name', function ($item) {
                return optional($item->expenseBy)->name;
            })
            ->filterColumn('date', function ($item) {
                if (request()->has('from_date') && request()->has('to_date')) {
                    $item->whereBetween('date', [request()->from_date, request()->to_date]);
                }
            })

            ->rawColumns(['action'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Expenses $model)
    {
        return $model->newQuery()->with(['category'])->select('expenses.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['order']    = [[2, 'desc']];

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '55px', 'class' => "text-center", 'printable' => false, 'exportable' => false, 'title' => __('custom.action')])
            ->dom('Bfrtip')
            // ->buttons(
            //     Button::make('create')->action("window.location = '".route('admin.expenses.create')."';"),
            //     Button::make('csv'),
            //     Button::make('excel'),
            //     Button::make('pdf'),
            //     Button::make('print'),
            //     Button::make('reload')
            // )
            ->parameters($params);;
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



    public function createAction()
    {
        return Button::make('create')->action("window.location = '".route('admin.expenses.create')."';");
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
            Column::make('title', 'title')->title(__('custom.title')),
            Column::make('date', 'date')->title(__('custom.date'))->class('width_10p'),
            Column::make('category.name', 'category.name')->title(__('custom.category')),
            Column::make('total', 'total')->title(__('custom.total')),
            Column::make('expenseBy.name', 'expenseBy.name')->title(__('custom.expense_user')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Expenses_' . date('YmdHis');
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
