<?php

namespace App\Services\Country;


use App\Models\SystemCountry;
use App\Services\BaseService;



/**
 * BrandService
 */
class CountryService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(SystemCountry $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
      return $this->model->paginate(10);
    }

}
