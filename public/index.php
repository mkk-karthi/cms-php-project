<?php
ini_set('display_errors', '1');

require_once "../config/database.php";
require_once "../Library/Router.php";
require_once "../Library/Helper.php";


require_once "../Controllers/UserController.php";

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

// user routes
$router->post('/api/users', [new UserController(), "list"], ["auth:admin"]);
$router->post('/api/user/create', [new UserController(), "create"]);
$router->get('/api/user/view/{id}', [new UserController(), "view"], ["auth:admin"]);
$router->post('/api/user/update/{id}', [new UserController(), "update"]);
$router->post('/api/user/delete/{id}', [new UserController(), "delete"], ["auth:admin"]);
$router->post('/api/user/approve', [new UserController(), "approve"], ["auth:admin"]);


$router->post('/api/login', [new UserController(), "login"]);
$router->post('/api/logout', [new UserController(), "logout"], ["auth"]);

$router->get('api/posts', function () {
    echo "posts!";
    exit;
});
$router->get('/post/{id}', function ($id) {
    echo "post-" . $id;
    exit;
});

$router->match();
