<?php

namespace App\Http\Controllers\Admin\Coupon;

use App\DataTables\CouponDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Services\Coupon\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;

        $this->middleware(['permission:List Coupon'])->only(['index']);
        $this->middleware(['permission:Add Coupon'])->only(['create']);
        $this->middleware(['permission:Edit Coupon'])->only(['edit']);
        $this->middleware(['permission:Delete Coupon'])->only(['destroy']);
    }

    public function index(CouponDataTable $dataTable)
    {
        set_page_meta(__('custom.coupon'));
        return $dataTable->render('admin.coupons.index');
    }

    public function create()
    {
        set_page_meta(__('custom.add_coupon'));
        return view('admin.coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();

        if ($coupon = $this->couponService->createOrUpdateWithFile($data, 'banner')) {
            flash(__('custom.coupon_create_successful'))->success();
        } else {
            flash(__('custom.coupon_create_failed'))->error();
        }
        if ($request->is_submit_set_product){
            return redirect()->route('admin.coupon.products', $coupon->id);
        }

        return redirect()->route('admin.coupons.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $coupon = $this->couponService->get($id);

        set_page_meta(__('custom.edit_coupon'));
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, $id)
    {
        $data = $request->validated();

        if ($this->couponService->createOrUpdateWithFile($data, 'banner', $id)) {
            flash(__('custom.coupon_updated_successful'))->success();
        } else {
            flash(__('custom.coupon_updated_failed'))->error();
        }

        return redirect()->route('admin.coupons.index');
    }

    public function destroy($id)
    {
        if ($this->couponService->delete($id)) {
            flash(__('custom.coupon_deleted_successful'))->success();
        } else {
            flash(__('custom.coupon_deleted_failed'))->error();
        }

        return redirect()->route('admin.coupons.index');
    }
    public function getActiveCouponByCode($code)
    {
        $coupon = $this->couponService->getActiveCouponByCode($code);
        if ($coupon) {
            return response()->json([
                'status'                => true,
                'coupon'                => $coupon,
                'coupon_product_ids'    => $coupon->couponProducts->pluck('product_id')->toArray()
            ]);
        } else {
            return response()->json([
                'status'                => false,
                'coupon'                => null,
                'coupon_product_ids'    => null
            ]);
        }
    }
}
