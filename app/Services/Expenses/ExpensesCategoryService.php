<?php

namespace App\Services\Expenses;

use App\Services\BaseService;
use App\Models\ExpensesCategory;

/**
 * ExpensesCategoryService
 */
class ExpensesCategoryService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(ExpensesCategory $model)
    {
        parent::__construct($model);
    }
    public function all()
    {
        return $this->model->orderBy('name')->paginate(10);
    }
}
