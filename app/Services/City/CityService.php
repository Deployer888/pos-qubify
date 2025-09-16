<?php

namespace App\Services\City;


use App\Models\SystemCity;
use App\Services\BaseService;



/**
 * BrandService
 */
class CityService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(SystemCity $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
      return $this->model->with(['state','state.country'])->orderBy('name')->paginate(10);
    }

}
