<?php


namespace App\Services\Purchase;


use Throwable;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Traits\SetModel;
use App\Models\Warehouse;
use App\Models\SystemCountry;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

/**
 * PurchaseServices
 */
class PurchaseServices extends BaseService
{
    use SetModel;

    /**
     * __construct
     *
     * @param  mixed $purchase
     * @return void
     */
    public function __construct(Purchase $purchase)
    {
        $this->model = $purchase;
    }

    /**
     * filterByDateRange
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $with
     * @return void
     */
    public function filterByDateRange($start = null, $end = null, $with = [])
    {
        try {
            $query = $this->model::query()->with($with);

            if ($start) {
                $query->whereDate('date', '>=', $start);
            }

            if ($end) {
                $query->whereDate('date', '<=', $end);
            }

            if (request('warehouse')){
                $query->where('warehouse_id', request('warehouse'));
            }

            return $query->orderBy('date', 'DESC')->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }


    public function allTime($with = [])
    {
        try {
            $query = $this->model::query()->with($with);

            if (request('warehouse')){
                $query->where('warehouse_id', request('warehouse'));
            }

            return $query->orderBy('date', 'DESC')->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * createCredentials
     *
     * @return array
     */
    public function createCredentials(): array
    {
        return [
            'suppliers' => Supplier::query()->where('status',Supplier::STATUS_ACTIVE)->get(['id', 'first_name', 'last_name']),
            'warehouses' => Warehouse::query()->where('status',Warehouse::STATUS_ACTIVE)->pluck('name', 'id'),
            'countries' => SystemCountry::query()->get(),
        ];
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return bool
     */
    public function store($request): bool
    {
        DB::transaction(function () use ($request) {

            $this->storePurchase($request)
                ->storePurchaseItems($request);
        });

        return true;
    }

    /**
     * purchaseNumber
     *
     * @param  mixed $supplier_id
     * @return string
     */
    private function purchaseNumber($supplier_id): string
    {
        return $supplier_id . date('His') . rand(3, 4);
    }

    /**
     * storePurchase
     *
     * @param  mixed $request
     * @return PurchaseServices
     */
    private function storePurchase($request): PurchaseServices
    {
        $this->model = $this->model
            ->newQuery()
            ->create([
                'purchase_number' => $this->purchaseNumber($request->supplier),
                'supplier_id'     => $request->supplier,
                'warehouse_id'    => $request->warehouse,
                'company'         => $request->company,
                'date'            => $request->date,
                'notes'           => $request->note,
                'total'           => $request->total,
                'status'          => $this->model::STATUS_REQUESTED,

                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'country'        => $request->country,
                'state'          => $request->state,
                'city'           => $request->city,
                'zipcode'        => $request->zipcode,
                'short_address'  => $request->short_address
            ]);

        return $this;
    }

    /**
     * storePurchaseItems
     *
     * @param  mixed $request
     * @return PurchaseServices
     */
    public function storePurchaseItems($request): PurchaseServices
    {
        if (isset($request->product_id) && is_array($request->product_id)) {
            $products = [];
            foreach ($request->product_id as $key => $product_id) {
                $products[] = [
                    'purchase_id'       => $this->model->id,
                    'product_id'        => $product_id,
                    'product_stock_id'  => $request->product_stock_id[$key],
                    'quantity'          => $request->quantity[$key],
                    'price'             => $request->price[$key],
                    'note'              => $request->product_note[$key],
                    'sub_total'         => $request->sub_total[$key],
                    'created_by'        => auth()->id(),
                    'updated_by'        => auth()->id(),
                ];
            }

            $this->model->purchaseItems()->insert($products);
        }

        return $this;
    }

    /**
     * editCredentials
     *
     * @return array
     */
    public function editCredentials(): array
    {
        return array_merge($this->createCredentials(), [
            'purchase' => $this->model->load(['purchaseItems.product','purchaseItems.productStock.attribute','purchaseItems.productStock.attributeItem']),
        ]);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @return bool
     */
    public function update($request): bool
    {
        DB::transaction(function () use ($request) {

            $this->purchaseUpdate($request)
                ->updatePurchaseItems($request);
        });

        return true;
    }

    /**
     * purchaseUpdate
     *
     * @param  mixed $request
     * @return void
     */
    private function purchaseUpdate($request)
    {
        $this->model->update([
            'purchase_number' => $this->model->purchase_number,
            'supplier_id'     => $request->supplier,
            'warehouse_id'    => $request->warehouse,
            'company'         => $request->company,
            'date'            => $request->date,
            'notes'           => $request->note,
            'total'           => $request->total,
            'status'          => $this->model::STATUS_REQUESTED,

            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country'        => $request->country,
            'state'          => $request->state,
            'city'           => $request->city,
            'zipcode'        => $request->zipcode,
            'short_address'  => $request->short_address
        ]);

        return $this;
    }

    /**
     * updatePurchaseItems
     *
     * @param  mixed $request
     * @return PurchaseServices
     */
    private function updatePurchaseItems($request): PurchaseServices
    {
        if (isset($request->product_id) && is_array($request->product_id)) {
            foreach ($request->product_id as $key => $product_id) {
                if ($request->purchase_item_id[$key] != null) {
                    $this->model
                        ->purchaseItems()
                        ->where('id', $request->purchase_item_id[$key])
                        ->update([
                            'product_id' => $product_id,
                            'product_stock_id'  => $request->product_stock_id[$key],
                            'quantity' => $request->quantity[$key],
                            'price' => $request->price[$key],
                            'note' => $request->product_note[$key],
                            'sub_total' => $request->sub_total[$key],
                        ]);
                } else {
                    $this->model
                        ->purchaseItems()
                        ->create([
                            'product_id' => $product_id,
                            'product_stock_id'  => $request->product_stock_id[$key],
                            'quantity' => $request->quantity[$key],
                            'price' => $request->price[$key],
                            'note' => $request->product_note[$key],
                            'sub_total' => $request->sub_total[$key],
                        ]);
                }
            }
        }

        return $this;
    }
}
