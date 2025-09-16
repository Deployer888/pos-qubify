<?php

namespace App\Http\Resources;

use App\Models\Purchase;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // dd($this);
        return [
            "id" => $this->id,
            "purchase_number" => $this->purchase_number,
            "supplier" => $this->supplier ? $this->supplier->full_name:null,
            'warehouse_name' => $this->warehouse ? $this->warehouse->name:null,
            "date" => $this->date,
            'total' => $this->total,
            'total_product' => $this->purchaseItems->count(),
            // 'is_received' => $this->received ? __t('received') : ($this->status ==Purchase::STATUS_CANCEL ? Purchase::STATUS_CANCEL : ($this->status ==Purchase::STATUS_REQUESTED ? Purchase::STATUS_REQUESTED : ($this->status ==Purchase::STATUS_CONFIRMED ? Purchase::STATUS_CONFIRMED :__t('not_received_yet')))),
            'is_received' => $this->received ? __t('received') : ($this->status ==Purchase::STATUS_CANCEL ? Purchase::STATUS_CANCEL : ($this->status ==Purchase::STATUS_REQUESTED ? Purchase::STATUS_REQUESTED : ($this->status ==Purchase::STATUS_CONFIRMED ? Purchase::STATUS_CONFIRMED :__t('not_received_yet')))),

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
            return [
                'status' => true,
                'text' => __t('missing'),
                'quantity' => $purchaseItemQty - $purchaseReceiveItemQty
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }
}
