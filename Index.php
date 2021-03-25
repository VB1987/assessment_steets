<?php

spl_autoload_register(function ($classname) {
    $filename = dirname(__FILE__) . '/classes/' . str_replace('\\', '/', $classname) . '.php';

    if (file_exists($filename)) {
        require_once $filename;
    }
});

$model = '';
$controller = '';
$view = '';

?><!DOCTYPE html>
<html lang="nl-NL">
    <head>
        <?= $view->meta(); ?>
    </head>

    <body>
        <div id="container">
            <div class="content">
                <?= $view->content(); ?>
            </div>
        </div>
    </body>
</html>