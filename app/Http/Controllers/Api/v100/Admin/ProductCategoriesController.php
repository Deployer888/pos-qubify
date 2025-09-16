<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\CategoriesResource;
use App\Services\Product\ProductCategoryService;
use App\Http\Requests\API\ProductCategoryRequest;
use App\Models\ProductCategory;

class ProductCategoriesController extends Controller
{
    use ApiReturnFormatTrait;

    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService){

        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $categories = CategoriesResource::collection($this->productCategoryService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Category List'), $categories);
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
    public function store(ProductCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $product_category = $this->productCategoryService->createOrUpdateWithFile($data, 'image');
            if($product_category == 'position_up')
            {
              return $this->responseWithError(__('custom.product_category_create_failed_for_limit_up'));

            }
            return $this->responseWithSuccess('Product Category Created', new CategoriesResource($product_category));
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
     try{
        $productCategory = $this->productCategoryService->get($id,'subCategory');
        if(!$productCategory){
            return $this->responseWithError('Not found',[],404);
        }
        return $this->responseWithSuccess('Product Category Details', new CategoriesResource($productCategory));
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
    public function update(ProductCategoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if($id == $data['parent_id']){
              return $this->responseWithError(__('custom.product_category_update_failed_for_parent'));
            }
            $product_category = $this->productCategoryService->createOrUpdateWithFile($data, 'image',$id);

            return $this->responseWithSuccess('Product Category Updated', new CategoriesResource($product_category));
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
            if ($this->productCategoryService->delete($id)) {
                return $this->responseWithSuccess(__('custom.product_category_deleted_successfully'));
            } else {
                return $this->responseWithError(__('custom.product_category_delete_failed'));
            }
        } catch(\Exception $e){
        return $this->responseWithError('Something went wrong', $e->getMessage());
     }

    }
}
