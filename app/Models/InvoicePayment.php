<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * InvoicePayment
 */
class InvoicePayment extends Model
{
    use HasFactory;

    /**
     * getDateAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getDateAttribute($value)
    {
        return custom_datetime($value);
    }


    /**
     * getPaymentTypeAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getPaymentTypeAttribute($value)
    {
        return strtoupper($value);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
