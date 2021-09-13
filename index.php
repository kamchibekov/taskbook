<?php

require_once("app/autoload.php");

use app\routes\Router;

Router::pathNotFound(function () {
    echo "404 Not Found :(";
});

Router::methodNotAllowed(function () {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "Method Not Allowed";
});

Router::add('/', 'TaskController/index');
Router::add('/task/create', 'TaskController/createTask', 'post');

Router::add('/auth/login', 'AuthController/login');

Router::run();