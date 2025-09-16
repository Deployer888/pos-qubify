<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Invoice
 */
class Invoice extends Model
{
    use HasFactory;

    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'customer' => 'array',
        'billing_info' => 'array',
        'shipping_info' => 'array',
        'items_data' => 'array',
        'bank_info' => 'array'
    ];

    // Status
    public const STATUS_PAID = 'paid';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_PENDING = 'pending';
    public const CREATED_FROM_ADMIN = 'admin';
    public const CREATED_FROM_CUSTOMER = 'customer';
    public const INVOICE_ALL_STATUS = [
        'paid' => 'Paid',
        'partially_paid' => 'Partially Paid',
        'overdue' => 'Overdue',
        'canceled' => 'Canceled',
        'pending' => 'Pending',
    ];

    public const DISCOUNT_FIXED = 'fixed';
    public const DISCOUNT_PERCENT = 'percent';

    public const PAYMENT_TYPE_CASH = 'cash';
    public const PAYMENT_TYPE_ONLINE = 'online';
    public const PAYMENT_TYPE_PAYPAL = 'paypal';
    public const PAYMENT_TYPE_STRIPE = 'stripe';
    public const PAYMENT_TYPE_BANK = 'bank';

    public const DELIVERY_STATUS_PENDING = 'pending';
    public const DELIVERY_STATUS_PROCESSING = 'processing';
    public const DELIVERY_STATUS_DELIVERED = 'delivered';
    public const DELIVERY_STATUS_CANCELED = 'canceled';


    // RELATIONS
    /**
     * items
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function saleReturns()
    {
        return $this->hasMany(SaleReturn::class, 'invoice_id');
    }

    /**
     * payments
     *
     * @return void
     */
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }

    // MUTATORS & ACCESSORS
    /**
     * getTotalPaidAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getTotalPaidAttribute($value)
    {
        return make2dec($value);
    }
    /**
     * getTotalAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getTotalAttribute($value)
    {
        return make2dec($value);
    }

    /**
     * getTokenAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getTokenAttribute($value)
    {
        return route('invoice.live_url', $value);
    }

    /**
     * customerInfo
     *
     * @return void
     */
    public function customerInfo()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    /**
     * warehouse
     *
     * @return void
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
