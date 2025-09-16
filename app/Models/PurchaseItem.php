<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PurchaseItem
 */
class PurchaseItem extends Model
{
    use HasFactory, CreatedByRelationship, UpdatedByRelationship, CreatedUpdatedBy;
    protected $casts = [
        'expiry_date' => 'date',
    ];
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'product_stock_id',
        'product_id',
        'quantity',
        'price',
        'note',
        'batch',
        'expiry_date',
        'sub_total',
        'created_by',
        'updated_by',
    ];

    /**
     * purchase
     *
     * @return void
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
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

    /**
     * receiveItems
     *
     * @return void
     */
    public function receiveItems()
    {
        return $this->hasMany(PurchaseItemReceive::class);
    }

    /**
     * returnItem
     *
     * @return void
     */
    public function returnItem()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
