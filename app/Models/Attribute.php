<?php

namespace App\Models;

use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Attribute
 */
class Attribute extends Model
{
    use HasFactory, ScopeActive;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /**
     * items
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(AttributeItem::class, 'attribute_id');
    }
}
