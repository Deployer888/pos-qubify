<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWalletHistory extends Model
{
    use HasFactory;
    const FROM_INVOICE_CREATE    = 'invoice_create';
    const FROM_INVOICE           = 'invoice';
    const FROM_INVOICE_RETURN    = 'invoice_return';

    protected $guarded = [];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function historyFrom(): MorphTo
    {
        return $this->morphTo('history_from');
    }
}
