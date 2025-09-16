<?php

namespace App\Services\Product;

use Throwable;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\InvoiceItem;
use App\Models\ProductStock;
use App\Services\BaseService;
use App\Models\SystemSettings;
use App\Models\DraftInvoiceItem;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Utils\FileUploadService;
use Illuminate\Database\Eloquent\Builder;

/**
 * ProductService
 */
class ProductService extends BaseService
{
    protected $fileUploadService;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $model = new Product();
        parent::__construct($model);
        $this->fileUploadService = app(FileUploadService::class);
    }

    public function wareHouseWiseProducts($with = null, $warehouse)
    {
        // Check if warehouse is null
        if (!$warehouse) {
            return collect(); // Return empty collection if no warehouse
        }

        return $this->model
            ->newQuery()
            ->with([$with => function($q) use($warehouse){
                $q->where('warehouse_id', $warehouse->id);
            }])
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('stock', function (Builder $builder) use ($warehouse) {
                $builder->where('warehouse_id', $warehouse->id)->where('quantity', '>', 0);
            })->get();
    }

    public function wareHouseWiseAllProductStocks($with = null, $warehouse)
    {
        // Check if warehouse is null
        if (!$warehouse) {
            return collect(); // Return empty collection if no warehouse
        }

        return ProductStock::
        when(Auth::guard('customer')->check(), function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->when(request()->route()->getPrefix() == 'api/v100/customer', function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->with($with)
        ->where('warehouse_id', $warehouse->id)
        ->where('quantity', '>', 0)
        ->where(function ($q) {
            $q->whereHas('product', function ($sub) {
                $sub->where('is_batch_product', 0);
            })
            ->orWhere(function ($q2) {
                $q2->whereHas('product', function ($sub2) {
                    $sub2->where('is_batch_product', 1);
                })->whereDate('expiry_date', '>=', date('Y-m-d'));
            });
        })
        ->get();
    }



    public function wareHouseWiseAllProductStocksPaginate($with = null, $warehouse)
    {
        // Check if warehouse is null
        if (!$warehouse) {
            return collect()->paginate(10); // Return empty paginated collection if no warehouse
        }

        return ProductStock::
            when(Auth::guard('customer')->check(), function($q){
                $q->whereHas('product', function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                });
            })
            ->when(Auth::guard('api_customer')->check(), function($q){
                $q->whereHas('product', function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                });
            })
            ->with($with)
            ->where('warehouse_id', $warehouse->id)
            ->where('quantity', '>', 0)
            ->paginate(10);
    }

    /**
     * createOrUpdate
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function createOrUpdate(array $data, $id = null)
    {
        try {
            if ($id) {
                try {
                    DB::beginTransaction();

                    $data['split_sale'] = array_key_exists('split_sale', $data) ? $data['split_sale'] : null;
                    $data['is_variant'] = array_key_exists('is_variant', $data) ? $data['is_variant'] : false;
                    $data['updated_by'] = Auth::id();
                    $object = $this->model->findOrFail($id);

                    if (isset($data['thumb']) && $data['thumb'] != null) {
                        if (strpos($data['thumb'], 'base64') !== false) {
                            $data['thumb'] = $this->fileUploadService->uploadBase64($data['thumb'], Product::FILE_STORE_PATH, $object->thumb);
                        }else{
                            $data['thumb'] = $this->uploadFile($data['thumb'], $object->thumb);
                        }
                    }

                    if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
                        $data['barcode_image'] = $this->processBarcodeImage($data['sku'], $data['barcode'], $data['barcode_image'], $object->barcode_image);
                    }

                    // Update product
                    $object->update($data);

                    // Product attribute
                    if (isset($data['attribute_data']) && count($data['attribute_data'])) {

//                        $product_invoice_stock_ids = InvoiceItem::where('product_id', $id)->pluck('product_stock_id')->toArray();
//                        $product_draft_invoice_stock_ids = DraftInvoiceItem::where('product_id', $id)->pluck('product_stock_id')->toArray();
//
//                        $used_stock_ids = array_unique(array_merge($product_invoice_stock_ids, $product_draft_invoice_stock_ids));
//
//                        $attr = ProductStock::find($used_stock_ids);
////                        $attr_ids = $attr->map->only(['attribute_id', 'attribute_item_id'])->values()->toArray();
//                        $attr_ids = $attr->map->only(['attribute_id', 'attribute_item_id'])->values()->toArray();
//                        dd($attr_ids);
                        // Delete old data(Not delete case stock_id used)
//                        ProductAttribute::where('product_id', $id)->delete();
                        // Store new data
                        foreach ($data['attribute_data'] as $item) {
                            if (isset($item['attribute_items'])) {
                                foreach ($item['attribute_items'] as $data) {
                                    ProductAttribute::updateOrCreate(
                                        [
                                            'product_id'        => $id,
                                            'attribute_id'      => $item['attribute'],
                                            'attribute_item_id' => $data,
                                        ],
                                        [
                                            'product_id'        => $id,
                                            'attribute_id'      => $item['attribute'],
                                            'attribute_item_id' => $data,
                                        ]
                                    );

                                }
                            }
                        }
                    }
                    DB::commit();
                    return true;
                } catch (Throwable $th) {
                    logger($th);
                    DB::rollBack();
                }
            } else {
                try {
                    DB::beginTransaction();
                    $data['created_by'] = Auth::id();
                    if (isset($data['thumb']) && $data['thumb'] != null) {
                        if (strpos($data['thumb'], 'base64') !== false) {
                            $data['thumb'] = $this->fileUploadService->uploadBase64($data['thumb'], Product::FILE_STORE_PATH);
                        }else {
                            $data['thumb'] = $this->uploadFile($data['thumb']);
                        }
                    }

                    if (isset($data['barcode_image']) && $data['barcode_image'] != null) {
                        $data['barcode_image'] = $this->processBarcodeImage($data['sku'], $data['barcode'], $data['barcode_image']);
                    }
                    // dd($data);
                    // Store product
                    $product = $this->model::create($data);

                    // Product attribute
                    if (isset($data['attribute_data']) && count($data['attribute_data'])) {
                        foreach ($data['attribute_data'] as $item) {
                            if (isset($item['attribute_items'])) {
                                foreach ($item['attribute_items'] as $data) {
                                    $a_item = new ProductAttribute();
                                    $a_item->product_id = $product->id;
                                    $a_item->attribute_id = $item['attribute'];
                                    $a_item->attribute_item_id = $data;
                                    $a_item->save();
                                }
                            }
                        }
                    }
                    DB::commit();
                    return $product;
                } catch (Throwable $th) {
                    logger($th);
                    DB::rollBack();
                }
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * processBarcodeImage
     *
     * @param  mixed $base64image
     * @param  mixed $old_name
     * @return void
     */
    public function processBarcodeImage($sku, $barcode, $base64image, $old_name = null)
    {
        // Store barcode image
        try {
            if ($old_name) {
                // Delete image
                try {
                    Storage::disk(config('filesystems.default'))->delete($this->model::BARCODE_STORE_PATH . '/' . $old_name);
                } catch (Throwable $th) {
                    //throw $th;
                }
            }
            $name = $sku . '_' . $barcode . '.png';
            $img = substr($base64image, strpos($base64image, ",") + 1);
            Storage::disk(config('filesystems.default'))->put($this->model::BARCODE_STORE_PATH . '/' . $name, base64_decode($img));
            return $name;
        } catch (Throwable $th) {
        }
    }


    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $data = $this->model::findOrFail($id);
            // Delete barcode
            try {
                Storage::disk(config('filesystems.default'))->delete($this->model::BARCODE_STORE_PATH . '/' . $data->barcode_image);
            } catch (Throwable $th) {
                //throw $th;
            }
            // Delete Product
            return $data->delete();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * getByBarcode
     *
     * @param  mixed $barcode
     * @return void
     */
    public function getByBarcode($barcode)
    {
        return $this->model::where('barcode', $barcode)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })->first();
    }
    public function getProductStockByBarcode($barcode)
    {
        return ProductStock::
        when(Auth::guard('customer')->check(), function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->whereHas('product', function ($q) use ($barcode){
            $q->where('barcode', $barcode);
        })
        ->first();
    }

    /**
     * getByCategory
     *
     * @param  mixed $category_id
     * @return void
     */
    public function getByCategory($category_id, $with = [])
    {
        try {

            return $this->model::with($with)
                ->when(Auth::guard('customer')->check(), function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                })
                ->whereHas('warehouseStockQty', function ($q){
                $q->where('quantity', '>', 0);
            })->where('category_id', $category_id)->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }
    public function getProductStockByCategory($category_id,$warehouse_id, $with = [])
    {
        try {
            // Check if warehouse_id is null or empty
            if (!$warehouse_id) {
                return collect(); // Return empty collection if no warehouse_id
            }

            return ProductStock::
            when(Auth::guard('customer')->check(), function($q){
                $q->whereHas('product', function($q){
                    $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
                });
            })
            ->whereHas('product', function ($q) use ($category_id){
                $q->where('category_id', $category_id);
            })
                ->with($with)
                ->where('warehouse_id', $warehouse_id)
                ->where('quantity', '>', 0)
                ->where(function ($q) {
                    $q->whereHas('product', function ($sub) {
                        $sub->where('is_batch_product', 0);
                    })
                    ->orWhere(function ($q2) {
                        $q2->whereHas('product', function ($sub2) {
                            $sub2->where('is_batch_product', 1);
                        })->whereDate('expiry_date', '>=', date('Y-m-d'));
                    });
                })
                ->get();

        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * getProductBySearch
     *
     * @param  mixed $q
     * @param  mixed $select
     * @param  mixed $with
     * @return void
     */
    public function getProductBySearch($q, $select = '*', $with = [])
    {
        return $this->model->select($select)->with($with)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('warehouseStockQty', function ($q){
            $q->where('quantity', '>', 0);
        })->where('sku', 'like', '%' . $q . '%')->get();
    }

    /**
     * getProductByNameSku
     *
     * @param  mixed $q
     * @param  mixed $select
     * @param  mixed $with
     * @return void
     */
    public function getProductByNameSku($q, $select = '*', $with = [])
    {
        $q = strtolower($q);
        return $this->model::select($select)
            ->with($with)
            ->when(Auth::guard('customer')->check(), function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            })
            ->whereHas('warehouseStockQty', function ($q){
                $q->where('quantity', '>', 0);
            })
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('sku', 'like', '%' . $q . '%')
            ->limit(10)
            ->get();
    }
    public function getProductStockByNameSku($search_q, $warehouse_id, $select = '*', $with = [])
    {
        $search_q = strtolower($search_q);

        // Check if warehouse_id is null or empty
        if (!$warehouse_id) {
            return collect(); // Return empty collection if no warehouse_id
        }

        return ProductStock::
        when(Auth::guard('customer')->check(), function($q){
            $q->whereHas('product', function($q){
                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
            });
        })
        ->whereHas('product', function ($q) use ($search_q){
            $q->where('name', 'like', '%' . $search_q . '%');
            $q->orWhere('sku', 'like', '%' . $search_q . '%');
            $q->orWhere('barcode', 'like', '%' . $search_q . '%');
        })
        ->with($with)
        ->where('warehouse_id', $warehouse_id)
        ->where('quantity', '>', 0)
        ->where(function ($q) {
            $q->whereHas('product', function ($sub) {
                $sub->where('is_batch_product', 0);
            })
            ->orWhere(function ($q2) {
                $q2->whereHas('product', function ($sub2) {
                    $sub2->where('is_batch_product', 1);
                })->whereDate('expiry_date', '>=', date('Y-m-d'));
            });
        })
        ->get();

//        return ProductStock::select($select)
//            ->with($with)
//            ->when(Auth::guard('customer')->check(), function($q){
//                $q->where('available_for', '!=', Product::SALE_AVAILABLE_FOR['warehouse']);
//            })
//            ->whereHas('warehouseStockQty', function ($q){
//                $q->where('quantity', '>', 0);
//            })
//            ->where('name', 'like', '%' . $q . '%')
//            ->orWhere('sku', 'like', '%' . $q . '%')
//            ->limit(10)
//            ->get();
    }

    public function lowStockList()
    {
        $products = Product::query()
            ->with('allStock')
            /*->when(request('product_id'), function ($q){
                $q->where('id', request('product_id'));
            })*/
            ->where('status', Product::STATUS_ACTIVE)
            ->get(['id', 'name', 'sku', 'barcode', 'price', 'stock_alert_quantity', 'thumb']);

        $productStockList = [];

        foreach ($products as $product){
            if (optional($product->allStock)->sum('quantity') < $product->stock_alert_quantity){
                $productStockList[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'price' => $product->price,
                    'image' =>  getStorageImage(Product::FILE_STORE_PATH, $product->thumb),
                    'stock' => optional($product->allStock)->sum('quantity') ?? 0,
                    'alert_quantity' => $product->stock_alert_quantity
                ];
            }
        }

        return [

            'total_product' => count($productStockList),
            'productStockList' => $productStockList

        ];
    }

    public function skuSettings()
    {
        $productSettings = SystemSettings::query()->where('settings_key', 'product_setting')->first();

        if ($productSettings){
            $productMaxId = (Product::query()->max('id')+1);

            $generatedSKU = $productSettings->settings_value['sku.prefix'].make8digits($productMaxId).$productSettings->settings_value['sku.suffix'];

            return [
                'auto' => $productSettings->settings_value['sku.auto'],
                'editable' => $productSettings->settings_value['sku.editable'],
                'generated_sku' => $generatedSKU
            ];
        }
        return [
            'auto' => null,
            'editable'=> null,
            'generated_sku' => null
        ];
    }
//    public function stockSkuSettings()
//    {
//        $productSettings = SystemSettings::query()->where('settings_key', 'product_setting')->first();
//        if ($productSettings){
//            return [
//                'auto'          => $productSettings->settings_value['sku.auto'],
//                'editable'      => $productSettings->settings_value['sku.editable'],
//                'sku_prefix'    => $productSettings->settings_value['sku.prefix'],
//                'sku_suffix'    => $productSettings->settings_value['sku.suffix']
//            ];
//        }
//        return [
//            'auto'          => null,
//            'editable'      => null,
//            'sku_prefix'    => null,
//            'sku_suffix'    => null
//        ];
//    }

    public function productAllWarehouseStock()
    {
        return $this->model
            ->newQuery()
            ->with('allStock.attribute', 'allStock.attributeItem', 'category')
            ->orderByDesc('id')
            ->get(['name', 'sku', 'barcode', 'price', 'category_id', 'id', 'stock_alert_quantity']);
    }
   
}
