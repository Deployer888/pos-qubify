<?php

namespace App\Models;

use App\Traits\ProductRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    use HasFactory, ProductRelationship;

    protected $fillable = [
        'coupon_id',
        'product_id',
    ];

}
