<?php

namespace App\Models;

use App\Traits\ModelBoot;
use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * ProductCategory
 */
class ProductCategory extends Model
{
    use HasFactory, ScopeActive, ModelBoot;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'image',
        'status',
        'parent_id',
        'position',
        'created_by',
        'updated_by'
    ];

    /**
     * appends
     *
     * @var array
     */
    protected $appends = ['text', 'file_url'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const FILE_STORE_PATH = 'product_categories';

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
     * getFileUrlAttribute
     *
     * @return void
     */
    public function getFileUrlAttribute()
    {
        return getStorageImage(self::FILE_STORE_PATH, $this->image);
    }

    public function parent_category()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id', 'id');
    }

    public function subCategory():HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id','id')->with('subCategory');
    }
}
