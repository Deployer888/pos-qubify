<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Throwable;

trait ModelBoot
{
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
                // Delete image form public directory
                if ($item->image) {
                    Storage::disk(config('filesystems.default'))->delete(self::FILE_STORE_PATH . '/' . $item->image);
                } else if ($item->avatar) {
                    Storage::disk(config('filesystems.default'))->delete(self::FILE_STORE_PATH . '/' . $item->avatar);
                } else if ($item->banner) {
                    Storage::disk(config('filesystems.default'))->delete(self::FILE_STORE_PATH . '/' . $item->banner);
                }
            } catch (Throwable $th) {
                throw $th;
            }
        });
    }
}
