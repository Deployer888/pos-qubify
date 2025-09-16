<?php

namespace App\Services\Utils;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * FileUploadService
 */
class FileUploadService
{

    /**
     * uploadFile
     *
     * @param  mixed $file
     * @param  mixed $upload_path
     * @param  mixed $delete_path
     * @return void
     */
    public function uploadFile($file, $upload_path = null, $delete_path = null, $use_original_name = false)
    {
        try {
            // Upload image
            // Delete old file
            if ($delete_path) {
                $this->delete($delete_path);
            }
            // Upload new file
            return $this->upload($file, $upload_path, $use_original_name);
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * upload
     *
     * @param  mixed $file
     * @param  mixed $path
     * @return void
     */
    public function upload($file, $path = 'others', $use_original_name = false)
    {
        try {
            if (!$use_original_name) {
                $name = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            } else {
                $full_name = $file->getClientOriginalName();
                $extract_name = explode('.', $full_name);

                $name = generateSlug($extract_name[0]) . '-' . time() . '.' . $file->getClientOriginalExtension();
            }
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * delete
     *
     * @param  mixed $path
     * @return void
     */
    public function delete($path = '')
    {
        try {
            // Delete image form public directory

                Storage::disk(config('filesystems.default'))->delete($path);


//            if (file_exists($path)) unlink($path);
        } catch (Exception $ex) {
        }
    }

    /**
     * @param string $base64string
     * @param string $path
     * @param string $disk
     * @param string $set_file_name
     * @return string
     */
    public function uploadBase64(string $base64string, string $path = 'others', $old='', string $set_file_name = ''): string
    {
        try {
            $disk = config('filesystems.default');
            if($old){
                $this->delete($path.'/'.$old);
            }
            // @phpstan-ignore-next-line
            $extension  = explode('/', explode(':', substr($base64string, 0, strpos($base64string, ';')))[1])[1];   // .jpg .png .pdf
            $replace    = substr($base64string, 0, strpos($base64string, ',') + 1);
            $image      = str_replace($replace, '', $base64string);
            $image      = str_replace(' ', '+', $image);
            $imageName  = Str::slug($set_file_name) . time() . rand(1111, 9999) . '.' . $extension;

            Storage::disk($disk)->put($path . '/' . $imageName, base64_decode($image));
            // @phpstan-ignore-next-line
            return $imageName ?? '';
        } catch (\Exception $e) {
            logger($e);
            return '';
        }
    }

}
