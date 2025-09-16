<?php

namespace App\Services\Product;

use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Services\BaseService;
use App\Models\ProductCategory;

/**
 * ProductCategoryService
 */
class ProductCategoryService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(ProductCategory $model)
    {
        parent::__construct($model);
    }

    /**
     * getParents
     *
     * @param  mixed $with
     * @return void
     */
    public function getParents($with = [])
    {
        try {
            return $this->model->with($with)->where('parent_id', null)->get();
        } catch (Throwable $th) {
            throw $th;
        }
    }
    public function createOrUpdateWithFile(array $data, $file_field_name, $id = null)
    {
        try {
            if ($id) {
                if (isset($data['parent_id']) && $data['parent_id'] != "") {
                    $parent_cat = $this->get($data['parent_id']);
                    $data['position'] = $parent_cat->position + 1;

                    if ($data['position'] > 2):
                        return 'position_up';
                    endif;

                }
                $data['updated_by'] = Auth::id();
                $object = $this->model->findOrFail($id);
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    if (strpos($data[$file_field_name], 'base64') !== false) {
                        $data[$file_field_name] = $this->fileUploadService->uploadBase64($data[$file_field_name], $this->model::FILE_STORE_PATH, $object->$file_field_name);
                    }else {
                        $data[$file_field_name] = $this->uploadFile($data[$file_field_name], $object->$file_field_name);
                    }
                }
                 $object->update($data);
                 return $object;
            } else {
                if (isset($data['parent_id']) && $data['parent_id'] != "") {
                    $parent_cat = $this->get($data['parent_id']);
                    $data['position'] = $parent_cat->position + 1;

                    if ($data['position'] > 2):
                        return 'position_up';
                    endif;
                }
                $data['created_by'] = Auth::id();
                if (isset($data[$file_field_name]) && $data[$file_field_name] != null) {
                    if (strpos($data[$file_field_name], 'base64') !== false) {
                        $data[$file_field_name] = $this->fileUploadService->uploadBase64($data[$file_field_name], $this->model::FILE_STORE_PATH);
                    }else {
                        $data[$file_field_name] = $this->uploadFile($data[$file_field_name]);
                    }

                }
                return $this->model::create($data);
            }
        } catch (Throwable $th) {
            throw $th;
        }
    }
    public function all(){
        return $this->model->with('subCategory')->orderBy('name')->paginate(10);
    }

}
