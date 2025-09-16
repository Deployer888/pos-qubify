<?php

namespace App\Models;

use App\Traits\ModelBoot;
use App\Traits\Scopes\ScopeActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Brand
 */
class Brand extends Model
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
        'created_by',
        'updated_by'
    ];

    protected $appends = ['text', 'file_url'];

    // CONST
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    public const FILE_STORE_PATH = 'brands';

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
}
