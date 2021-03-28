<?php

namespace app;

class Route
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $controller;

    /**
     * @var string
     */
    public $method;

    /**
     * Route constructor.
     * @param string $path
     * @param string $controller
     * @param string $method
     */
    public function __construct(string $path, string $controller, string $method)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->method = $method;
    }
}