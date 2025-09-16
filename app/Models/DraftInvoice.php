<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DraftInvoice extends Model
{
    use HasFactory;

    protected $casts = [
        'customer'      => 'array',
        'billing_info'  => 'array',
        'shipping_info' => 'array',
        'items_data'    => 'array',
        'bank_info'     => 'array'
    ];


    // Status
    public const STATUS_PAID            = 'paid';
    public const STATUS_PARTIALLY_PAID  = 'partially_paid';
    public const STATUS_OVERDUE         = 'overdue';
    public const STATUS_CANCELED        = 'canceled';
    public const STATUS_PENDING         = 'pending';
    public const CREATED_FROM_ADMIN     = 'admin';
    public const CREATED_FROM_CUSTOMER  = 'customer';
    public const INVOICE_ALL_STATUS     = [
        'paid'              => 'Paid',
        'partially_paid'    => 'Partially Paid',
        'overdue'           => 'Overdue',
        'canceled'          => 'Canceled',
        'pending'           => 'Pending',
        'paid'              => 'Paid',
    ];
    public const PAYMENT_TYPE_CASH = 'cash';
    public const PAYMENT_TYPE_ONLINE = 'online';
    public const PAYMENT_TYPE_PAYPAL = 'paypal';
    public const PAYMENT_TYPE_STRIPE = 'stripe';
    public const PAYMENT_TYPE_BANK = 'bank';

    public const DISCOUNT_FIXED     = 'fixed';
    public const DISCOUNT_PERCENT   = 'percent';

    // RELATIONS
    public function items()
    {
        return $this->hasMany(DraftInvoiceItem::class, 'draft_invoice_id');
    }


    // MUTATORS & ACCESSORS

    public function getTotalPaidAttribute($value)
    {
        return make2decimal($value);
    }

    public function getTotalAttribute($value)
    {
        return make2decimal($value);
    }

    public function getTokenAttribute($value)
    {
        return route('invoice.live_url', $value);
    }

    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }

    public function customerInfo()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
