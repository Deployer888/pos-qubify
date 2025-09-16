<?php

namespace App\Services\Warehouse;

use App\Models\Warehouse;
use App\Services\BaseService;

/**
 * WarehouseService
 */
class WarehouseService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Warehouse $model)
    {
        parent::__construct($model);
    }

    public function defaultWareHouse()
    {
        return $this->model->newQuery()->where('is_default', 1)->first();
    }

    public function getWareHouse($wareHouseId)
    {
        return $this->model->newQuery()->where('id', $wareHouseId)->where('status', Warehouse::STATUS_ACTIVE)->first();
    }

    public function pluck()
    {
        return $this->model->newQuery()->where('status', Warehouse::STATUS_ACTIVE)->pluck('name', 'id');
    }

    public function firstWarehouse()
    {
        return $this->model->newQuery()->where('status', Warehouse::STATUS_ACTIVE)->first();
    }
    public function all()
    {
      return $this->model->orderBy('status','asc')->paginate(10);
    }

}
