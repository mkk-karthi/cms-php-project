<?php
ini_set('display_errors', '1');

require_once "../config/database.php";
require_once "../Library/Router.php";

session_start();

// define routers
$router = new Router();

$router->get('/', function () {
    include "../Views/index.html";
    exit;
});

$router->get('api/', function () {
    echo "Welcome!";
    exit;
});

$router->get('/api/users', function () {
    echo "users";
    exit;
});

$router->get('/posts', function () {
    echo "posts!";
    exit;
});
$router->get('/post/{id}', function ($id) {
    echo "post-" . $id;
    exit;
});

$router->match();
