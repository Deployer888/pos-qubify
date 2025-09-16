<?php

namespace App\Models;

use App\Traits\ModelBoot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AttributeItem
 */
class AttributeItem extends Model
{
    use HasFactory, ModelBoot;

    protected $appends = ['file_url'];

    public const FILE_STORE_PATH = 'attribute_items';


    // MUTATORS & ACCESSORS    
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
