<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductAttribute
 */
class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_item_id',
    ];

    // RELATIONS
    /**
     * attribute
     *
     * @return void
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    /**
     * attribute_item
     *
     * @return void
     */
    public function attribute_item()
    {
        return $this->belongsTo(AttributeItem::class, 'attribute_item_id');
    }
}
