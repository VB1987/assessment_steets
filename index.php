<?php

    spl_autoload_register(function ($classname) {
    //    $filename = dirname(__FILE__) . str_replace('\\', '/', $classname) . '.php';
        $filename = str_replace('\\', '/', $classname) . '.php';

        if (file_exists($filename)) {
            require_once $filename;
        }
    });

    $router = new Router();
    $router->register(new Route('assessment/prime-number/get', '\classes\controllers\PrimeNumberController', 'getPrimeNumber'));
    $router->register(new Route('assessment', '\classes\controllers\PrimeNumberController', 'show'));
    $router->handleRequest($_SERVER['REQUEST_URI']);
?>