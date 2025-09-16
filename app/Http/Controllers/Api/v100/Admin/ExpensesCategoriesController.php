<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Requests\API\ExpensesCategoryRequest;
use App\Http\Resources\ExpensesCategoriesResource;
use App\Models\ExpensesCategory;
use App\Services\Expenses\ExpensesCategoryService;

class ExpensesCategoriesController extends Controller
{
    use ApiReturnFormatTrait;
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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $expenses_categories = ExpensesCategoriesResource::collection($this->expensesCategoryService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Expenses Category List'), $expenses_categories);

           } catch(\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

           }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesCategoryRequest $request)
    {
        try{
            $data = $request->validated();
            $expenses_category =$this->expensesCategoryService->createOrUpdate($data);
             return $this->responseWithSuccess('Expenses Category Created',new ExpensesCategoriesResource($expenses_category));

        } catch(\Exception $e)
        {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $expenses_category =$this->expensesCategoryService->get($id);
            if(!$expenses_category)
            return $this->responseWithError('Not found',[],404);

            return $this->responseWithSuccess('Expenses Category Details',new ExpensesCategoriesResource($expenses_category));

        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }



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
        try{
            $data = $request->validated();
            $this->expensesCategoryService->createOrUpdate($data,$id);
            $expenses_category = $this->expensesCategoryService->get($id);
            return $this->responseWithSuccess('Expenses Category Updated',new ExpensesCategoriesResource($expenses_category));

        } catch(\Exception $e)
        {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($this->expensesCategoryService->delete($id)) {
                return $this->responseWithSuccess(__('custom.expenses_category_deleted_successful'),[],200);
            } else {
                return $this->responseWithError(__('custom.expenses_category_deleted_failed'),[],500);
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', [],500);

        }
    }
}
