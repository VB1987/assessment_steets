<?php

class Controller
{
    private $model;

    /**
     * Controller constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getPrimeNumbers()
    {

    }
}