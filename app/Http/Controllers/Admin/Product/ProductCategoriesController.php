<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\DataTables\ProductCategoryDataTable;
use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use App\Services\Product\ProductCategoryService;
use App\Imports\ProductCategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ProductCategoriesController extends Controller
{
    protected $productCategoryService;

    /**
     * __construct
     *
     * @param  mixed $productCategoryService
     * @return void
     */
    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;

        $this->middleware(['permission:List Product Category'])->only(['index']);
        $this->middleware(['permission:Add Product Category'])->only(['create']);
        $this->middleware(['permission:Edit Product Category'])->only(['edit']);
        $this->middleware(['permission:Delete Product Category'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductCategoryDataTable $dataTable)
    {
        set_page_meta(__('custom.product_category'));
        return $dataTable->render('admin.product_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->productCategoryService->getParents('subCategory');

        set_page_meta(__('custom.add_product_category'));
        return view('admin.product_categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryRequest $request)
    {
        $data = $request->validated();

        if ($product_category = $this->productCategoryService->createOrUpdateWithFile($data, 'image')) {
            if($product_category == 'position_up'){
                flash(__('custom.product_category_create_failed_for_limit_up'))->error();
            }
            flash(__('custom.product_category_created_successfully'))->success();
        } else {
            flash(__('custom.product_category_create_failed'))->error();
        }

        return redirect()->route('admin.product-categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_category = $this->productCategoryService->get($id);
        $categories = $this->productCategoryService->getParents();

        set_page_meta(__('custom.edit_product_category'));
        return view('admin.product_categories.edit', compact('product_category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryRequest $request, $id)
    {
        $data = $request->validated();

        if($id == $data['parent_id']){
            flash(__('custom.product_category_update_failed_for_parent'))->error();
            return redirect()->back();
        }

        if ($this->productCategoryService->createOrUpdateWithFile($data, 'image', $id)) {
            flash(__('custom.product_category_updated_successfully'))->success();
        } else {
            flash(__('custom.product_category_update_failed'))->error();
        }

        return redirect()->route('admin.product-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->productCategoryService->delete($id)) {
            flash(__('custom.product_category_deleted_successfully'))->success();
        } else {
            flash(__('custom.product_category_delete_failed'))->error();
        }

        return redirect()->route('admin.product-categories.index');
    }

    /**
     * Bulk import product categories from Excel file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $import = new ProductCategoryImport;
            Excel::import($import, $request->file('import_file'));

            flash(__('custom.import_success'))->success();

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Collect all row-specific error messages
            $messages = [];

            foreach ($e->errors() as $msg) {
                $messages[] = $msg; // Already includes row info from ProductImport
            }

            return redirect()->route('admin.product-categories.index')
                ->withErrors($messages);

        } catch (\Exception $e) {
            // Log or flash unexpected exceptions
            flash(__('custom.import_failed') . ': ' . $e->getMessage())->error();
        }

        return redirect()->route('admin.product-categories.index');
    }
}
