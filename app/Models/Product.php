<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product
 */
class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'barcode',
        'barcode_image',
        'brand_id',
        'manufacturer_id',
        'model',
        'price',
        'weight',
        'weight_unit_id',
        'dimension_l',
        'dimension_w',
        'dimension_d',
        'measurement_unit_id',
        'notes',
        'desc',
        'thumb',
        'is_variant',
        'status',
        'tax_status',
        'custom_tax',
        'created_by',
        'updated_by',
        'stock',
        'is_variant',
        'is_batch_product',
        'split_sale',
        'available_for',
        'customer_buying_price',
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['thumb_url'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const TAX_INCLUDED = 'included';
    public const TAX_EXCLUDED = 'excluded';

    public const FILE_STORE_PATH = 'products';
    public const BARCODE_STORE_PATH = 'product_barcodes';

    public const SALE_AVAILABLE_FOR = [
        'all'       => 'all',
        'customer'  => 'customer',
        'warehouse' => 'warehouse',
    ];

    public function invoiceItem()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    /**
     * totalSale
     *
     * @return void
     */
    public function totalSale()
    {
        return InvoiceItem::where('product_id', $this->id)->sum('sub_total');
    }
    public function totalSaleQty()
    {
        return InvoiceItem::where('product_id', $this->id)->sum('quantity');
    }

    /**
     * getThumbUrlAttribute
     *
     * @return void
     */
    public function getThumbUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->thumb);
    }

    // RELATIONS
    /**
     * category
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * attributes
     *
     * @return void
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * stock
     *
     * @return void
     */
    public function stock()
    {
        return $this->hasOne(ProductStock::class);
    }
    public function productStock()
    {
        return $this->hasOne(ProductStock::class);
    }

    /**
     * warehouseStock
     *
     * @param  mixed $warehouse
     * @return void
     */
    public function warehouseStock($warehouse)
    {
        return $this->stock()
            ->where('warehouse_id', $warehouse)
            ->first()
            ->quantity ?? 0;
    }

    public function warehouseStockQty()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }

    public function weight_unit()
    {
        return $this->belongsTo(WeightUnit::class, 'weight_unit_id', 'id');
    }
    

    public function allStock()
    {
        return $this->hasMany(ProductStock::class);
    }
    public function measurement_unit(){
        return $this->belongsTo(MeasurementUnit::class);

    }
    public function brand(){
        return $this->belongsTo(brand::class);

    }
}
