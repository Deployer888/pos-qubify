<?php

namespace App\Traits;

trait SetModel
{
    /**
     * __call
     *
     * @param  mixed $name
     * @param  mixed $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        $this->model->{$name}($arguments);
    }

    /**
     * setModel
     *
     * @param  mixed $model
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * getModel
     *
     * @return void
     */
    public function getModel()
    {
        return $this->model;
    }
}
