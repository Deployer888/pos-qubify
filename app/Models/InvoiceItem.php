<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * InvoiceItem
 */
class InvoiceItem extends Model
{
    use HasFactory;

    protected $casts = [
        'bank_info' => 'array'
    ];

    /**
     * salesReturnItems
     *
     * @return HasMany
     */
    public function salesReturnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItems::class);
    }

    /**
     * returnQuantity
     *
     * @return void
     */
    public function returnQuantity()
    {
        return $this->salesReturnItems()->sum('return_qty');
    }

    /**
     * invoice
     *
     * @return BelongsTo
     */
    /**
     * invoice
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }


    /**
     * product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
