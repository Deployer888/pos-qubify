<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepotStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'product_name',
        'measurement_unit',
        'current_stock',
        'price',
        'customer_price',
        'barcode',
        'barcode_image'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'price' => 'decimal:2',
        'customer_price' => 'decimal:2'
    ];

    /**
     * Get the depot that owns this stock.
     */
    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Get all sale items for this stock.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(DepotSaleItem::class);
    }

    /**
     * Update stock quantity
     */
    public function adjustStock(float $quantity, string $adjustmentType = 'Add'): bool
    {
        if ($adjustmentType === 'Add') {
            $this->current_stock += $quantity;
        } else {
            if ($this->current_stock < $quantity) {
                return false;
            }
            $this->current_stock -= $quantity;
        }
        
        return $this->save();
    }
}
