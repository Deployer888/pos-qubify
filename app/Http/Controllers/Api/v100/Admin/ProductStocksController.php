<?php

namespace App\Http\Controllers\Api\v100\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Product\ProductService;
use App\Services\Supplier\SupplierService;
use App\Services\Warehouse\WarehouseService;
use App\Services\Product\ProductStockService;
use App\Http\Requests\API\ProductStockRequest;
use App\Http\Resources\ProductStocksDetailResource;

class ProductStocksController extends Controller
{
    use ApiReturnFormatTrait;
    protected $productService;
    protected $warehouseService;
    protected $productStockService;
    protected $supplierService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        ProductService $productService,
        WarehouseService $warehouseService,
        ProductStockService $productStockService,
        SupplierService $supplierService
    ) {
        $this->productService = $productService;
        $this->warehouseService = $warehouseService;
        $this->productStockService = $productStockService;
        $this->supplierService = $supplierService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            $product = $this->productService->get($id, ['attributes.attribute',
                'attributes.attribute_item', 'weight_unit']);

            if (!$product) {
                return $this->responseWithError('Product not found',[],404);

            }

            $warehouses = $this->warehouseService->getActiveData();
            $old_stocks = $this->productStockService->getProductStock($id);
            $suppliers = $this->supplierService->getActiveData();
            $product_stock_details = [
                'product' => $product,
                'warehouses' => $warehouses,
                'old_stocks' => $old_stocks,
                'suppliers' => $suppliers
            ];

           return $this->responseWithSuccess('Product Stock Details', new ProductStocksDetailResource($product_stock_details));

            } catch(\Exception $e){
                return $this->responseWithError('Something went wrong',$e->getMessage());
            }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStockRequest $request, $id)
    {

        $data = $request->validated();
      try {
        $product = $this->productService->get($id);
        if (!$product) {
            return $this->responseWithError('Product not found',[],404);

        }

        if ($product->is_variant) {
            $result = $this->productStockService->variantStockUpdate($data, $id,$request);
        } else {
            $result = $this->productStockService->normalStockUpdate($data, $id,$request);
        }

        if ($result) {
            return $this->responseWithSuccess(__('custom.product_stock_update_successfully'));
        } else {
            return $this->responseWithError(__('custom.product_stock_update_failed'));
        }
      } catch(\Exception $e){
        return $this->responseWithError('Something went wrong',$e->getMessage());

      }


    }
    public function updateByStock(Request $request,$id){
        $data = $request->all();
        $product = $this->productService->get($id);
        if (!$product) {
            return $this->responseWithError('Product not found',[],404);

        }
        $result = $this->productStockService->updateByStock($data, $id);
        if ($result) {
            return $this->responseWithSuccess(__('custom.product_stock_update_successfully'));
        } else {
            return $this->responseWithError(__('custom.product_stock_update_failed'));
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
        //
    }
}
