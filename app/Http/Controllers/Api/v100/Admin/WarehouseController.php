<?php

namespace App\Http\Controllers\Api\v100\Admin;

use PDF;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Http\Resources\WarehouseResource;
use App\Http\Requests\API\WarehouseRequest;
use App\Services\Warehouse\WarehouseService;


class WarehouseController extends Controller
{
    use ApiReturnFormatTrait;
    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService){
        $this->warehouseService = $warehouseService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $warehouses = WarehouseResource::collection($this->warehouseService->all())->response()->getData(true);
            return $this->responseWithSuccess(__('Warehouse List'), $warehouses);
        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        try{
            $data = $request->validated();
            $warehouse =$this->warehouseService->createOrUpdate($data);

             return $this->responseWithSuccess('Warehouse Created',new WarehouseResource($warehouse));

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
    public function show(Warehouse $warehouse)
    {
        try{
            $warehouse->load('product_stocks.product.category',
                'product_stocks.product.manufacturer',
                'product_stocks.product.weight_unit',
                'product_stocks.attribute',
                'product_stocks.attributeItem');

            return $this->responseWithSuccess('Warehouse Details',new WarehouseResource($warehouse));

        } catch(\Exception $e)
        {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }
    public function showPdf(Warehouse $warehouse)
    {

        $warehouse_details = $warehouse->load('product_stocks.product.category', 'product_stocks.product.manufacturer');

        // set_page_meta($warehouse->name);

        $pdf = PDF::loadView('admin.warehouses.show-pdf', compact('warehouse_details'));
        return $pdf->download($warehouse->name.'_details' . '.pdf');

        // return view('admin.warehouses.show-pdf', compact('warehouse_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        try{

            $data = $request->validated();
            if (!isset($data['is_default'])) $data['is_default'] = false;
            $this->warehouseService->createOrUpdate($data, $id);
            $warehouse =$this->warehouseService->get($id);
             return $this->responseWithSuccess('Warehouse Updated',new WarehouseResource($warehouse));

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
            if ($this->warehouseService->delete($id)) {
                return $this->responseWithSuccess(__('custom.warehouse_delete_successfully'));
            } else {
                return $this->responseWithError(__('custom.warehouse_delete_failed'));
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(__('custom.this_record_already_used'));
        }
    }
}
