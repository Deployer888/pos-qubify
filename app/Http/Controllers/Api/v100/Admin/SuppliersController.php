<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\SupplierResource;
use App\Http\Requests\API\SupplierRequest;
use App\Services\Supplier\SupplierService;
use App\Http\Resources\SupplierDetailsResource;

class SuppliersController extends Controller
{
    protected $supplierService;
    use ApiReturnFormatTrait;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;

    }
    public function index()
    {
        try{
            $suppliers = SupplierResource::collection($this->supplierService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Supplier List'), $suppliers);

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
    public function store(SupplierRequest $request)
    {
        try {
            $data = $request->validated();
            $supplier = $this->supplierService->createOrUpdateWithFile($data, 'avatar');
            return $this->responseWithSuccess('Supplier Created',new SupplierResource($supplier));

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
    public function show(Supplier $supplier)
    {
        try{
            $supplier_details = $this->supplierService->supplierShowDetails($supplier);
            return $this->responseWithSuccess('Supplier Details',new SupplierDetailsResource($supplier_details));


        } catch(\Exception $e)
        {
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
    public function update(SupplierRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $this->supplierService->createOrUpdateWithFile($data, 'avatar', $id);
            $supplier = $this->supplierService->get($id);
            return $this->responseWithSuccess('Supplier Updated',new SupplierResource($supplier));

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
            if ($this->supplierService->delete($id)) {
                return $this->responseWithSuccess(__('custom.supplier_deleted_successfully'));
            } else {
                return $this->responseWithError(__('custom.supplier_delete_failed'));
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
}
