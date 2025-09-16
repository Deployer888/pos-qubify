<?php

namespace App\Models;

use App\Traits\CreatedByRelationship;
use App\Traits\CreatedUpdatedBy;
use App\Traits\ProductRelationship;
use App\Traits\UpdatedByRelationship;
use App\Traits\WarehouseRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductStockHistory extends Model
{
    use HasFactory, CreatedUpdatedBy, ProductRelationship, WarehouseRelationship, CreatedByRelationship, UpdatedByRelationship;

    protected $guarded = ['id'];

    const TYPE_IN   = 'in';
    const TYPE_OUT  = 'out';

    const ACTION_FROM_STOCK_UPDATE      = 'stock_update';
    const ACTION_FROM_PURCHASE_RECEIVE  = 'purchase_receive';
    const ACTION_FROM_PURCHASE_RETURN   = 'purchase_return';
    const ACTION_FROM_INVOICE           = 'invoice';
    const ACTION_FROM_INVOICE_RETURN    = 'invoice_return';

    public function product_stock()
    {
        return $this->belongsTo(ProductStock::class);
    }
    public function historyFrom() : MorphTo
    {
        return $this->morphTo('history_from');
    }
}
