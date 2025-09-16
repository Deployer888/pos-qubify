<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleReturnCreatableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'invoice_id' => make8digits($this->id),
            'warehouse' => optional($this->warehouse)->name,
            'date' => custom_date($this->date),
            'customer' => !empty($this->customer_id) ? $this->customer['full_name'] : 'Walk-In Customer',
            'total' => currencySymbol() . make2decimal($this->total),
            'total_paid' => currencySymbol() . make2decimal($this->total_paid),
            'payment_type' => $this->payment_type,
            'status' => $this->status,
            'delivery_status' => $this->delivery_status,

        ];
    }
}
