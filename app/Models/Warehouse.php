<?php

namespace App\Models;

use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Warehouse
 */
class Warehouse extends Model
{
    use HasFactory, ScopeActive;

    /**
     * fillable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['text'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const IS_DEFAULT = true;

    public const FILE_STORE_PATH = 'warehouses';


    // MUTATORS & ACCESSORS
    /**
     * getTextAttribute
     *
     * @return void
     */
    public function getTextAttribute()
    {
        return $this->name;
    }
    /**
     * getNameAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function product_stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'warehouse_id', 'id')->orderByDesc('id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'warehouse_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'warehouse_id', 'id');
    }
}
