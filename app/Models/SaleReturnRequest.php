<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'warehouse_id',
        'return_date',
        'return_note',
        'return_total_amount',
        'items_info',
        'status',
        'requested_by',
        'status_updated_by',
        'status_updated_at',
        'created_by',
        'updated_by'
    ];
    const STATUS_PENDING    = 'pending';
    const STATUS_ACCEPTED   = 'accepted';
    const STATUS_REJECTED   = 'rejected';

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function saleReturnRequestItems(): HasMany
    {
        return $this->hasMany(SaleReturnItemRequest::class, 'sale_return_request_id', 'id');
    }
}
