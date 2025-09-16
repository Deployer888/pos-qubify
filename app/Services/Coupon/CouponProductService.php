<?php

namespace App\Services\Coupon;

use App\Models\CouponProduct;
use App\Services\BaseService;

/**
 * BrandService
 */
class CouponProductService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(CouponProduct $model)
    {
        parent::__construct($model);
    }
    public function all($id){
        return $this->model->with('product')->where('coupon_id',$id)->select('coupon_products.*')->paginate(10);
    }
    public function getActiveCouponsProducts($active_coupons){
        return $this->model->whereIn('coupon_id',$active_coupons)->get();
    }
}
