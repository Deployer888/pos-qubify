<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DraftInvoiceItem extends Model
{
    use HasFactory;

    protected $casts = [
        'bank_info' => 'array'
    ];

    /**
     * invoice
     *
     * @return BelongsTo
     */
    public function draftInvoice(): BelongsTo
    {
        return $this->belongsTo(DraftInvoice::class);
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
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
