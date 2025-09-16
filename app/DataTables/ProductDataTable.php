<?php

namespace App\DataTables;

use PDF;
use App\Models\Product;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

/**
 * ProductDataTable
 */
class ProductDataTable extends DataTable
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
            ->addColumn('id', function ($row) {
                return '<input type="checkbox" class="product_checkbox" name="product_id[]" value="' . $row->id . '">';
            })
            ->addColumn('action', function ($item) {
                $buttons = '';
                $buttons .= '<a class="dropdown-item" href="' . route('admin.products.show', $item->id) . '" title="Show"><i class="fa fa-eye"></i> ' . __('custom.show') . ' </a>';
                if (auth()->user()->can('Edit Product')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.products.edit', $item->id) . '" title="Edit"><i class="mdi mdi-square-edit-outline"></i> ' . __('custom.edit') . ' </a>';
                }
                if (auth()->user()->can('Stock Product')) {
                    $buttons .= '<a class="dropdown-item" href="' . route('admin.product-stocks.edit', $item->id) . '" title="Stock And Price"><i class="mdi mdi-format-list-bulleted"></i> ' . __('custom.stock_and_price') . ' </a>';
                }

                // $buttons .= '<a class="dropdown-item update-stock" data-id="' . $item->id . '" href="#" title="Update Stock"><i class="mdi mdi-stack-exchange"></i> ' . __('custom.update_stock') . ' </a>';

                $buttons .= '<a class="dropdown-item" href="' . route('admin.products.barcode.download', $item->id) . '" title="Download Barcode"><i class="mdi mdi-download"></i> ' . __('custom.download_barcode') . ' </a>';
                if (auth()->user()->can('Delete Product')) {
                    $buttons .= '<form action="' . route('admin.products.destroy', $item->id) . '"  id="delete-form-' . $item->id . '" method="post" >
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
            ->editColumn('is_variant', function ($item) {
                if ($item->is_variant) {
                    return '<span class="badge badge-success">Yes</span>';
                } else {
                    return '<span class="badge badge-secondary">No</span>';
                }
            })
              ->editColumn('is_batch_product', function ($item) {
                if ($item->is_batch_product) {
                    return '<span class="badge badge-success">Yes</span>';
                } else {
                    return '<span class="badge badge-secondary">No</span>';
                }
            })
            ->editColumn('name', function ($item) {
                return '<a class="btn btn-link" href="' . route('admin.products.show', $item->id) . '">' . $item->name . '</a>';
            })
            ->editColumn('price', function ($item) {
                return currencySymbol() . make2decimal($item->price);
            })
            ->editColumn('stock', function ($item) {
                return $item->stock . ' ' . optional($item->weight_unit)->name;
            })
            ->editColumn('category.name', function ($item) {
                return $item->category->name ?? '';
            })
            ->editColumn('thumb', function ($item) {
                return '<img class="img-64" src="' . $item->thumb_url . '" alt="' . $item->name . '" />';
            })->editColumn('status', function ($item) {
                $badge = $item->status == Product::STATUS_ACTIVE ? "badge-success" : "badge-danger";
                return '<span class="badge ' . $badge . '">' . Str::upper($item->status) . '</span>';
            })
            /*->editColumn('tax_status', function ($item) {
                return Str::upper($item->tax_status);
            })*/
            ->rawColumns(['tax_status', 'status', 'thumb', 'action', 'id', 'name', 'is_variant','is_batch_product'])->addIndexColumn();
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->with(['category', 'manufacturer', 'weight_unit'])->newQuery()->select('products.*');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $params             = $this->getBuilderParameters();
        $params['order']    = [[3, 'asc']];

        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(
                [
                    'width' => '55px',
                    'class' => "text-center",
                    'printable' => false,
                    'exportable' => false,
                    'title' => __('custom.action'),
                    'scrollY' => '320px'
                ],
            )
            ->parameters($params);
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

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('DT_RowIndex', __('custom.sl')),
            Column::make('id', 'id')
                ->orderable(false)
                ->searchable(false)
                ->printable(false)
                ->exportable(false)
                ->className('select_all_checkbox')
                ->title('<input type="checkbox" class="select_all"/>')
                ->render(''),
            Column::make('thumb', 'thumb')->title(__('custom.thumb')),
            Column::make('name', 'name')->title(__('custom.product_name')),
            Column::make('sku', 'sku')->title(__('custom.sku')),
            Column::make('category.name', 'category.name')->title(__('custom.category')),
            Column::make('price', 'price')->title(__('custom.price')),
            Column::make('stock', 'stock')->title(__('custom.stock_quantity')),
            Column::make('is_variant', 'is_variant')->title(__('custom.variant')),
            Column::make('is_batch_product', 'is_batch_product')->title(__('custom.batch')),
            /*Column::make('tax_status', 'tax_status')->title(__('custom.tax')),*/
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
        return 'Product_' . date('YmdHis');
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
