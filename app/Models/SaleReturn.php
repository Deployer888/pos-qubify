<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * SaleReturn
 */
class SaleReturn extends Model
{
    use CreatedByRelationship, UpdatedByRelationship;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id', 'return_date', 'return_note', 'return_total_amount', 'items_info', 'created_by', 'updated_by'
    ];

    /**
     * invoice
     *
     * @return BelongsTo
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * saleReturnItems
     *
     * @return HasMany
     */
    public function saleReturnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItems::class, 'sale_return_id', 'id');
    }
}
