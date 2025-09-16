<?php

namespace App\Models;

use App\Traits\ProductRelationship;
use App\Traits\WarehouseRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductStock
 */
class ProductStock extends Model
{
    use HasFactory, ProductRelationship, WarehouseRelationship;

    protected $fillable = [
        'quantity',
        'product_id',
        'warehouse_id',
        'attribute_id',
        'attribute_item_id',
        'price',
        'batch',
        'expiry_date',
        'customer_buying_price',
        'supplier_id'
    ];

    protected $appends = ['price_for_sale'];

    /**
     * attribute
     *
     * @return BelongsTo
     */
    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * attributeItem
     *
     * @return BelongsTo
     */
    public function attributeItem(): BelongsTo
    {
        return $this->belongsTo(AttributeItem::class, 'attribute_item_id', 'id');
    }
    public function getPriceForSaleAttribute()
    {
        if (auth()->guard('customer')->check()) {
            return ($this->customer_buying_price > 0 || $this->customer_buying_price != null) ?
                $this->customer_buying_price : (($this->price > 0 || $this->price != null) ? $this->price : (($this->product->customer_buying_price > 0 || $this->product->customer_buying_price != null) ? $this->product->customer_buying_price : $this->product->price));
        } else {
            return ($this->price > 0 || $this->price != null) ? $this->price : (($this->product->price > 0 || $this->product->price != null) ? $this->product->price : 0);
        }
    }
}
