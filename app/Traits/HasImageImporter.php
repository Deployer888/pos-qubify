<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasImageImporter
{
    public function importImage($image, $old_name = null, $upload_path = null)
    {
        $imageValue = trim($image ?? '');
        $storedPath = null;

        if ($imageValue) {
            if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
                try {
                    $imageContent = file_get_contents($imageValue);
                    $ext = pathinfo(parse_url($imageValue, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename =Str::random(10) . '.' . $ext;
                    Storage::disk('public')->put($upload_path . '/' . $filename, $imageContent);
                    $storedPath = $filename;
                } catch (\Exception $e) {
                    logger('Failed to download image from URL: {$imageValue} | ' . $e->getMessage());
                    throw $e;
                }
            }
        }

        return $storedPath;
    }
}
