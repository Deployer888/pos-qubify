<?php

namespace App\Services\Supplier;

use App\Models\Purchase;
use App\Services\BaseService;
use App\Models\Supplier;

/**
 * SupplierService
 */
class SupplierService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Supplier $model)
    {
        parent::__construct($model);
    }

    public function supplierShowDetails($supplier)
    {
        $purchase = Purchase::query()
            ->with('purchaseItems', 'warehouse', 'supplier')
            ->latest('date')
            ->where('supplier_id', $supplier->id);

        return [
            'supplier' => $supplier,
            'purchases' => $purchase->simplePaginate(100),
            'products' => $this->products($purchase->get())
        ];
    }

    public function products($purchases)
    {
        $products = [];
        foreach ($purchases as $purchase){
            foreach ($purchase->purchaseItems as $item){
                $products[] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ];
            }
        }

        return $products;
    }
    public function all()
    {
        return $this->model->orderBy('first_name','asc')->paginate(10);
    }
}
