<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * PurchaseReceive
 */
class PurchaseReceive extends Model
{
    use HasFactory, CreatedByRelationship, UpdatedByRelationship, CreatedUpdatedBy;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'receive_date', 'total', 'created_by', 'updated_by'
    ];

    /**
     * purchase
     *
     * @return BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * purchaseItemReceives
     *
     * @return HasMany
     */
    public function purchaseItemReceives(): HasMany
    {
        return $this->hasMany(PurchaseItemReceive::class);
    }
}
