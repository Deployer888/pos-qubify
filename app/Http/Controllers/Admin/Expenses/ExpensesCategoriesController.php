<?php

namespace App\Http\Controllers\Admin\Expenses;

use App\Http\Controllers\Controller;
use App\DataTables\ExpensesCategoryDataTable;
use App\Http\Requests\ExpensesCategoryRequest;
use App\Services\Expenses\ExpensesCategoryService;

class ExpensesCategoriesController extends Controller
{
    protected $expensesCategoryService;

    /**
     * __construct
     *
     * @param  mixed $expensesCategoryService
     * @return void
     */
    public function __construct(ExpensesCategoryService $expensesCategoryService)
    {
        $this->expensesCategoryService = $expensesCategoryService;

        $this->middleware(['permission:List Expenses Category'])->only(['index']);
        $this->middleware(['permission:Add Expenses Category'])->only(['create']);
        $this->middleware(['permission:Edit Expenses Category'])->only(['edit']);
        $this->middleware(['permission:Delete Expenses Category'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExpensesCategoryDataTable $dataTable)
    {
        set_page_meta(__('custom.expenses_category'));
        return $dataTable->render('admin.expenses_categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        set_page_meta(__('custom.add_expenses_category'));
        return view('admin.expenses_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesCategoryRequest $request)
    {
        $data = $request->validated();

        if ($this->expensesCategoryService->createOrUpdate($data)) {
            flash(__('custom.expenses_category_create_successful'))->success();
        } else {
            flash(__('custom.expenses_category_create_failed'))->error();
        }

        return redirect()->route('admin.expenses-categories.index');
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
        $expenses_category = $this->expensesCategoryService->get($id);

        set_page_meta(__('custom.edit_expenses_category'));
        return view('admin.expenses_categories.edit', compact('expenses_category',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensesCategoryRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->expensesCategoryService->createOrUpdate($data, $id)) {
            flash(__('custom.expenses_category_updated_successful'))->success();
        } else {
            flash(__('custom.expenses_category_updated_failed'))->error();
        }

        return redirect()->route('admin.expenses-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->expensesCategoryService->delete($id)) {
            flash(__('custom.expenses_category_deleted_successful'))->success();
        } else {
            flash(__('custom.expenses_category_deleted_failed'))->error();
        }

        return redirect()->route('admin.expenses-categories.index');
    }
}
