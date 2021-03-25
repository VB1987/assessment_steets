<?php

class View
{
    private $model;
    private $controller;

    /**
     * View constructor.
     * @param Model $model
     * @param Controller $controller
     */
    public function __construct(Model $model, Controller $controller)
    {
        $this->model = $model;
        $this->controller = $controller;
    }

    public function index()
    {
        include $this->model->get
    }

    public function meta()
    {
        include $this->model->getMeta();
    }
}