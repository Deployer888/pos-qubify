<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepotCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'adhaar_no',
        'ration_card_no',
        'card_range',
        'name',
        'mobile',
        'age',
        'is_family_head',
        'address',
        'depot_id',
        'status'
    ];

    protected $casts = [
        'age' => 'integer',
        'is_family_head' => 'boolean'
    ];

    /**
     * Get the depot this customer belongs to.
     */
    public function depot(): BelongsTo
    {
        return $this->belongsTo(Depot::class);
    }

    /**
     * Get all sales made to this customer.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(DepotSale::class);
    }

    /**
     * Get all family members for this customer's family.
     */
    public function familyMembers()
    {
        return $this->where('family_id', $this->family_id)
                    ->where('depot_id', $this->depot_id)
                    ->where('id', '!=', $this->id)
                    ->get();
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include family heads.
     */
    public function scopeFamilyHeads($query)
    {
        return $query->where('is_family_head', true);
    }
}
