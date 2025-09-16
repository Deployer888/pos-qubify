<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseHistoryResource extends JsonResource
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
            "id" => $this->id,
            "purchase_number" => $this->purchase_number,
            "date" => date('F m, Y',
            strtotime($this->date)),
            'created_at' => date('H:i:s A',
            strtotime($this->created_at)),
            'total' => currencySymbol().' '. $this->total,
            'total_product' => $this->purchaseItems->count(),
            'status'  => \Illuminate\Support\Str::upper($this->status),
            'is_received' => $this->received ? true : false,
            'is_missing' => $this->checkMissing($this)


        ];

    }
    public function checkMissing($purchase)
    {
        $purchaseItemQty = $purchase->purchaseItems->sum('quantity');
        $purchaseReceiveItemQty = 0;

        foreach ($purchase->purchaseItems as $purchaseItem) {
            $purchaseReceiveItemQty += $purchaseItem->receiveItems->sum('quantity');
        }
        if ($purchase->received && $purchaseItemQty != $purchaseReceiveItemQty) {
            return true;
        } else {
            return false;
        }
    }
}
