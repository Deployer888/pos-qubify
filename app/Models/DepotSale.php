<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepotSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'depot_customer_id',
        'invoice_no',
        'subtotal',
        'tax',
        'total',
        'note'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Get the depot that made this sale.
     */
    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Get the customer this sale was made to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(DepotCustomer::class, 'depot_customer_id');
    }

    /**
     * Get the items in this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(DepotSaleItem::class);
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'DEPOT-';
        $date = now()->format('Ymd');
        $lastSale = self::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastSale ? (intval(substr($lastSale->invoice_no, -4)) + 1) : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
