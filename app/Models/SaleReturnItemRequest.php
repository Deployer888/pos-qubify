<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleReturnItemRequest extends Model
{
    use HasFactory;

    public $fillable = [
        'sale_return_id',
        'invoice_item_id',
        'product_stock_id',
        'product_id',
        'product_name',
        'return_qty',
        'return_price',
        'return_sub_total',
        'created_by',
        'updated_by',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function saleReturnRequest(): BelongsTo
    {
        return $this->belongsTo(SaleReturnRequest::class, 'sale_return_request_id', 'id');
    }
    public function productStock(): BelongsTo
    {
        return $this->belongsTo(ProductStock::class);
    }
}
