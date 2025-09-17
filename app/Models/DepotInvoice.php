<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepotInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'invoice_number',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function items()
    {
        return $this->hasMany(DepotInvoiceItem::class, 'invoice_id');
    }

    public function getStatusColorAttribute()
    {
        return [
            'pending' => 'warning',
            'paid' => 'success',
            'cancelled' => 'danger',
        ][$this->status] ?? 'secondary';
    }
}
