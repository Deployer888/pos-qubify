<?php

namespace App\Traits;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait WarehouseRelationship
{
    /**
     * warehouse
     *
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
