<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppliedCouponResource extends JsonResource
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
            'status'                => true,
            'id'                    => $this->id,
            'title'                 => $this->title,
            'code'                  => $this->code,
            'minimum_shopping'      => $this->minimum_shopping,
             'maximum_discount'     => $this->maximum_discount,
             'discount_type'        => $this->discount_type,
             'discount'             => $this->discount,
              'coupon_status'       => $this->status,
              'start_date'          => $this->start_date,
              'end_date'            => $this->end_date,
              'validity'            => $this->getValidity($this),


            'coupon_product_ids'    => $this->couponProducts->pluck('product_id')->toArray()
        ];
    }
    public function getValidity($coupon)
    {
             $startDate  = \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d');
                $endDate    = \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d');
//                $check      = \Carbon\Carbon::now()->between($startDate,$endDate);
                if($startDate <= \Carbon\Carbon::now()->format('Y-m-d') && $endDate >= \Carbon\Carbon::now()->format('Y-m-d')){
                    $data   = Carbon::parse($coupon->start_date)->format('d M Y') . ' - ' . Carbon::parse($coupon->end_date)->format('d M Y');
                }else {
                    $data   =  Carbon::parse($coupon->start_date)->format('d M Y') . ' - ' . Carbon::parse($coupon->end_date)->format('d M Y');
                }
                return $data;
    }
}
