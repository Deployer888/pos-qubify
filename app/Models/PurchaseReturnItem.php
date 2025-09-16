<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PurchaseReturnItem
 */
class PurchaseReturnItem extends Model
{
    use HasFactory, CreatedUpdatedBy, CreatedByRelationship, UpdatedByRelationship;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_return_id',
        'purchase_item_id',
        'product_id',
        'product_stock_id',
        'quantity',
        'price',
        'sub_total',
        'created_by',
        'updated_by',
    ];

    /**
     * purchaseReturn
     *
     * @return void
     */
    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
