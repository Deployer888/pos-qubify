<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\UpdatedByRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Purchase
 */
class Purchase extends Model
{
    use HasFactory, CreatedByRelationship, UpdatedByRelationship, CreatedUpdatedBy;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'purchase_number',
        'supplier_id',
        'warehouse_id',
        'company',
        'date',
        'notes',
        'total',
        'status',
        'created_by',
        'updated_by',
        'address_line_1',
        'address_line_2',
        'country',
        'state',
        'city',
        'zipcode',
        'received',
        'cancel_date',
        'cancel_by',
        'cancel_note',
        'short_address'
    ];

    public const STATUS_REQUESTED = 'requested';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_CONFIRMED = 'confirmed';

    /**
     * supplier
     *
     * @return void
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * warehouse
     *
     * @return void
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * purchaseItems
     *
     * @return void
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * systemCountry
     *
     * @return void
     */
    public function systemCountry()
    {
        return $this->belongsTo(SystemCountry::class, 'country');
    }

    /**
     * systemState
     *
     * @return void
     */
    public function systemState()
    {
        return $this->belongsTo(SystemState::class, 'state');
    }

    /**
     * systemCity
     *
     * @return void
     */
    public function systemCity()
    {
        return $this->belongsTo(SystemCity::class, 'city');
    }

    /**
     * purchaseReceives
     *
     * @return void
     */
    public function purchaseReceives()
    {
        return $this->hasMany(PurchaseReceive::class);
    }
}
