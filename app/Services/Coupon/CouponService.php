<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use App\Services\BaseService;

/**
 * BrandService
 */
class CouponService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Coupon $model)
    {
        parent::__construct($model);
    }
    public function getActiveCouponByCode($code)
    {
        try {
            $coupon = $this->model->where('status', Coupon::STATUS_ACTIVE)
                ->with('couponProducts')
                ->where('code', $code)
                ->first();

            if ($coupon) {
                // dd($coupon)
                $startDate  = \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d');
                $endDate    = \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d');
                // dd($startDate,$coupon->start_date,$endDate,$coupon->end_date);
                if ($startDate <= \Carbon\Carbon::now()->format('Y-m-d') && $endDate >= \Carbon\Carbon::now()->format('Y-m-d')) {
                    return $coupon;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }

    }
    public function all()
    {
        return $this->model->orderBy('title')->paginate(10);
    }
}
