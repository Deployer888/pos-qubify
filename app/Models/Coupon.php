<?php

namespace App\Models;

use App\Traits\ModelBoot;
use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory, ScopeActive, ModelBoot;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const FILE_STORE_PATH = 'coupons';

    protected $fillable = [
        'title',
        'code',
        'banner',
        'minimum_shopping',
        'maximum_discount',
        'discount_type',
        'discount',
        'status',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];

    protected $appends = ['banner_url'];

    public function getBannerUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->banner);
    }
    public function couponProducts()
    {
        return $this->hasMany(CouponProduct::class);
    }
}
