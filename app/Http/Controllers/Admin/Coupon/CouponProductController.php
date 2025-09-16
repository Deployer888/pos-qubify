<?php

namespace App\Http\Controllers\Admin\Coupon;

use App\DataTables\CouponProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponProductRequest;
use App\Services\Coupon\CouponProductService;
use App\Services\Coupon\CouponService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class CouponProductController extends Controller
{
    protected $couponService;
    protected $productService;
    protected $couponProductService;

    public function __construct(CouponService $couponService,ProductService $productService,CouponProductService $couponProductService)
    {
        $this->couponService            = $couponService;
        $this->productService           = $productService;
        $this->couponProductService     = $couponProductService;

        $this->middleware(['permission:List Coupon'])->only(['index']);
        $this->middleware(['permission:Add Coupon'])->only(['create']);
        $this->middleware(['permission:Edit Coupon'])->only(['edit']);
        $this->middleware(['permission:Delete Coupon'])->only(['destroy']);
    }

    public function index(CouponProductDataTable $dataTable,$id)
    {

        set_page_meta(__('custom.coupon'));
        $products   = $this->productService->get();
        $coupon     = $this->couponService->get($id);
        return $dataTable->render('admin.coupons.coupon-products.index',compact('products','coupon'));
    }
    public function store(CouponProductRequest $request)
    {
        $data = $request->validated();

        if ($this->couponProductService->createOrUpdate($data)) {
            flash(__('custom.coupon_product_create_successful'))->success();
        } else {
            flash(__('custom.coupon_product_create_failed'))->error();
        }


        return redirect()->back();
    }

    public function destroy($id)
    {

        if ($this->couponProductService->delete($id)) {
            flash(__('custom.coupon_product_deleted_successful'))->success();
        } else {
            flash(__('custom.coupon_product_deleted_failed'))->error();
        }

        return redirect()->back();
    }
}
