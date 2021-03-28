<?php

namespace app;

class Router
{
    private $routes = [];

    public function register(Route $route)
    {
        $this->routes[] = $route;
    }

    public function handleRequest(string $request)
    {
        $matches = [];
        foreach ($this->routes as $route) {
            if (preg_match('/' . str_replace('/', '\/', $route->path) . '/', $request, $matches)) {
                array_shift($matches);

                $class = new \ReflectionClass($route->controller);
                $method = $class->getMethod($route->method);
                $instance = $class->newInstance(...$matches);
                $method->invoke($instance);

                return;
            }
        }

        throw new RuntimeException("The request '$request' did not match any route.");
    }
}