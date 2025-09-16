<?php

namespace App\Models;

use App\Traits\ProductRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PurchaseItemReceive
 */
class PurchaseItemReceive extends Model
{
    use HasFactory, ProductRelationship;

    /**
     * fillable
     *
     * @var array
     */
    public $fillable = [
        'purchase_receive_id', 'purchase_item_id', 'product_id',
        'quantity', 'price', 'sub_total','product_stock_id',
    ];

    /**
     * purchaseReceive
     *
     * @return BelongsTo
     */
    public function purchaseReceive(): BelongsTo
    {
        return $this->belongsTo(PurchaseReceive::class);
    }

    /**
     * purchaseItem
     *
     * @return BelongsTo
     */
    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseItem::class);
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
