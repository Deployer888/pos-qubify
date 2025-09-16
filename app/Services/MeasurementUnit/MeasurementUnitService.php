<?php

namespace App\Services\MeasurementUnit;

use App\Models\MeasurementUnit;
use App\Services\BaseService;
use Auth;
/**
 * MeasurementUnitService
 */
class MeasurementUnitService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(MeasurementUnit $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
      return $this->model->paginate(10);
    }

}
