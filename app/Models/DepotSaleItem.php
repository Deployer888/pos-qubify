<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepotSaleItem extends Model
{
    protected $fillable = [
        'depot_sale_id',
        'depot_stock_id',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Get the sale this item belongs to.
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(DepotSale::class, 'depot_sale_id');
    }

    /**
     * Get the stock item that was sold.
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(DepotStock::class, 'depot_stock_id');
    }

    /**
     * Calculate total for this item
     */
    public function calculateTotal(): float
    {
        return $this->quantity * $this->price;
    }
}
