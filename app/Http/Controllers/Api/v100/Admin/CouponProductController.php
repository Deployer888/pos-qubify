<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use App\Services\Coupon\CouponService;
use App\Services\Product\ProductService;
use App\Services\Coupon\CouponProductService;
use App\Http\Resources\CouponProductsResource;
use App\Http\Requests\API\CouponProductRequest;
use App\Http\Resources\CouponProductsDetailsResource;

class CouponProductController extends Controller
{
    use ApiReturnFormatTrait;

    protected $couponService;
    protected $productService;
    protected $couponProductService;

    public function __construct(CouponService $couponService, ProductService $productService, CouponProductService $couponProductService)
    {
        $this->couponService = $couponService;
        $this->productService = $productService;
        $this->couponProductService = $couponProductService;
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
    public function store(CouponProductRequest $request)
    {
        try{
            $data = $request->validated();
            $coupon_product =$this->couponProductService->createOrUpdate($data);
            return $this->responseWithSuccess('Coupon Product Created',new CouponProductsResource($coupon_product));

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
            $coupon_products = $this->couponProductService->all($id);
            $products = $this->productService->get();
            $coupon = $this->couponService->get($id);
            $coupon_products_details = ['coupon_products' => $coupon_products, 'products' => $products, 'coupon' => $coupon];
            return $this->responseWithSuccess(__('Coupon Product Details'), new CouponProductsDetailsResource($coupon_products_details));

        } catch (\Exception $e) {
            return $this->responseWithError('Something went wrong', $e->getMessage());

        }

    }
    public function getActiveCouponProducts()
    {
        try {
            // $coupon_products = $this->couponProductService->all($id);
            // $products = $this->productService->get();
            $coupons = $this->couponService->get();
            $coupon_products = [];
            if (count($coupons)) {
                $active_coupons = [];
                foreach($coupons as $coupon){
                    $is_active_coupon = $this->couponService->getActiveCouponByCode($coupon->code);
                    if($is_active_coupon){
                        $active_coupons [] =  $is_active_coupon->id;

                    }
                }
                $coupon_products = $this->couponProductService->getActiveCouponsProducts($active_coupons);


            }
            $coupon_resources = CouponProductsResource::collection($coupon_products)->response()->getData(true);
            return $this->responseWithSuccess(__('Active Coupons Products Details'), $coupon_resources);

        } catch (\Exception $e) {
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
    public function update(Request $request, $id)
    {
        //
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
            if ($this->couponProductService->delete($id)) {
                return $this->responseWithSuccess(__('custom.coupon_product_deleted_successful'),[],200);
            } else {
                return $this->responseWithError(__('custom.coupon_product_deleted_failed'),[],500);
            }
        } catch(\Exception $e){
            return $this->responseWithError('Something went wrong',[],500);

        }
    }
}
