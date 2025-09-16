<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Brand\BrandService;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\BrandResource;
use App\Http\Requests\API\BrandRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends Controller
{
    use ApiReturnFormatTrait;
    protected $brandService;
    public function __construct(BrandService $brandService){

        $this->brandService = $brandService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $brands = BrandResource::collection($this->brandService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Brand List'), $brands);

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
    public function store(BrandRequest $request)
    {

        try{
            $data = $request->validated();
            $brand =$this->brandService->createOrUpdateWithFile($data, 'image');
             return $this->responseWithSuccess('Brand Created',new BrandResource($brand));

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

            $brand = $this->brandService->get($id);
            if(!$brand)
            return $this->responseWithError('Brand not found', [], 404);

            return $this->responseWithSuccess('Brand details', new BrandResource($brand));
        } catch (\Exception $e) {
            // Handle the exception, for example, return a 404 response
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
    public function update(BrandRequest $request, $id)
    {
        try{
            $data = $request->validated();
            $this->brandService->createOrUpdateWithFile($data, 'image',$id);
            $brand =$this->brandService->get($id);
             return $this->responseWithSuccess('Brand Updated',new BrandResource($brand));

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
            if ($this->brandService->delete($id)) {
                return $this->responseWithSuccess(__('custom.brand_deleted_successful'));
            } else {
                return $this->responseWithError(__('custom.brand_deleted_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
