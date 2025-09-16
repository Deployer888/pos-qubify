<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'depot_type',
        'address',
        'city',
        'state',
        'status'
    ];

    /**
     * Get the user that manages this depot.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all stocks in this depot.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(DepotStock::class);
    }

    /**
     * Get all customers registered with this depot.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(DepotCustomer::class);
    }

    /**
     * Get all sales made by this depot.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(DepotSale::class);
    }

    /**
     * Scope a query to only include active depots.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function statename()
    {
        return $this->belongsTo(State::class, 'state');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id'); // or 'manager_id'
    }
}
