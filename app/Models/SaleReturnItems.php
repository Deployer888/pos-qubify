<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SaleReturnItems
 */
class SaleReturnItems extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    public $fillable = [
        'sale_return_id', 'invoice_item_id', 'product_id', 'product_name',
        'return_qty', 'return_price', 'return_sub_total', 'created_by', 'updated_by',
    ];

    /**
     * product
     *
     * @return void
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
