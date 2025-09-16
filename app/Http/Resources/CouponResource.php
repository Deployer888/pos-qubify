<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [

            'id' => $this->id,
            'banner' => $this->banner,
            'banner_url' => $this->banner_url,
            'title' => $this->title,
            'code' => $this->code,
            'minimum_shopping' => $this->minimum_shopping,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'status' => $this->getStatus($this),
            'validity' => $this->getValidity($this),
        ];
    }
    public function getValidity($coupon)
    {
        $startDate = Carbon::parse($coupon->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($coupon->end_date)->format('Y-m-d');
        return Carbon::parse($coupon->start_date)->format('d M Y') . ' - ' . Carbon::parse($coupon->end_date)->format('d M Y');
    }
    public function getStatus($coupon)
    {
        $startDate = Carbon::parse($coupon->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($coupon->end_date)->format('Y-m-d');
//                $check      = Carbon::now()->between($startDate,$endDate);
        if ($startDate <= Carbon::now()->format('Y-m-d') && $endDate >= Carbon::now()->format('Y-m-d')) {
           return Str::upper(Coupon::STATUS_ACTIVE);
        } else {
            return Str::upper(Coupon::STATUS_INACTIVE);
        }
    }
}
