<?php

namespace App\Services\Manufacturer;

use App\Models\Manufacturer;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;

/**
 * ManufacturerService
 */
class ManufacturerService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Manufacturer $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
      return $this->model->orderBy('status','asc')->paginate(10);
    }

}
