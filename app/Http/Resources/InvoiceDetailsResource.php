<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Call the parent method to include other properties
        $data = parent::toArray($request);
        if ($this->items->isNotEmpty()) {
        // Add the 'return_quantity' field to each item in the 'items' key
        $data['items'] = $this->items->map(function ($item) {
            // Assuming you have a method named 'returnQuantity' on your item model
            return array_merge($item->toArray(), [
                'return_quantity' => $item->returnQuantity(),
            ]);
        });
      }

        return $data;
    }
}
