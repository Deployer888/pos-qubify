<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Throwable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * ExpensesFile
 */
class ExpensesFile extends Model
{
    use HasFactory;

    protected $fillable = ['expenses_id', 'original_name', 'file_name'];

    protected $appends = ['full_path'];

    /**
     * boot
     *
     * @return void
     */
    public static function boot()
    {

        parent::boot();

        static::deleted(function ($item) {
            try {
                // Delete file form public directory
                Storage::disk(config('filesystems.default'))->delete(Expenses::FILE_STORE_PATH . '/' . $item->file_name);
            } catch (Throwable $th) {
                //throw $th;
            }
        });
    }


    /**
     * getFullPathAttribute
     *
     * @return void
     */
    public function getFullPathAttribute()
    {
        return getStorageFile(Expenses::FILE_STORE_PATH, $this->file_name);
    }
}
