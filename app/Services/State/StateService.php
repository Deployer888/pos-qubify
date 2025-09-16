<?php

namespace App\Services\State;


use App\Models\SystemState;
use App\Services\BaseService;



/**
 * BrandService
 */
class StateService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(SystemState $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
      return $this->model->with('country')->paginate(10);
    }

}
