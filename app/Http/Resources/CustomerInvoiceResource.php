<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerInvoiceResource extends JsonResource
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
          'date' => $this->date,
          'total' => $this->total,
          'total_paid' => $this->total_paid,
          'payment_type' => $this->payment_type,
          'status' => $this->status
        ];
    }
}
