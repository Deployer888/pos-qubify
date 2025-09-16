<?php

namespace App\Http\Resources;

use App\Models\Purchase;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    //    dd($this);
        return [
            'purchase_number' => $this->purchase_number,
            'supplier' => $this->supplier ? new SupplierResource($this->supplier):null,
            'warehouse' => $this->warehouse ? new WarehouseResource($this->warehouse) :null,
            'company' => $this->company,
            'date' => date('Y-m-d', strtotime($this->date)),
            'notes' => $this->notes,
            'short_address' => $this->short_address,
             'zipcode' => $this->zipcode,
            'address' => [
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'country' => $this->systemCountry ? new CountriesResource($this->systemCountry) : null,
                'state' => $this->systemState ? new StatesResource($this->systemState) : null,
                'city' => $this->systemCity ? new CitiesResource($this->systemCity) : null,
            ],
            'status' => $this->getStatusValue(),
            'is_received' =>  $this->received ? __t('received') : ($this->status ==Purchase::STATUS_CANCEL ? Purchase::STATUS_CANCEL : ($this->status ==Purchase::STATUS_REQUESTED ? Purchase::STATUS_REQUESTED : ($this->status ==Purchase::STATUS_CONFIRMED ? Purchase::STATUS_CONFIRMED :__t('not_received_yet')))),

            // 'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),
            'purchase_items' => PurchaseItemResource::collection($this->whenLoaded('purchaseItems')),

            'total' => make2decimal($this->total),
        ];
    }

    protected function getStatusValue()
    {
        switch ($this->status) {
            case \App\Models\Purchase::STATUS_REQUESTED:
                return ucfirst($this->status);
            case \App\Models\Purchase::STATUS_CONFIRMED:
                return ucfirst($this->status);
            default:
                return ucfirst($this->status);
        }
    }
}
