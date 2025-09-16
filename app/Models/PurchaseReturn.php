<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PurchaseReturn
 */
class PurchaseReturn extends Model
{
    use HasFactory, CreatedUpdatedBy, CreatedByRelationship, UpdatedByRelationship;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'return_date',
        'note',
        'total',
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
     * purchaseReturnItems
     *
     * @return void
     */
    public function purchaseReturnItems()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }
}
