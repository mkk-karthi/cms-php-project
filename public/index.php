<?php

// start the session
session_start();

define('APP_PATH', realpath(__DIR__ . '/..'));
define('PUPLIC_PATH', __DIR__);

// get env variables
$env = parse_ini_file('../.env');
define('APP_NAME', $env["APP_NAME"]);
define('DEBUG', $env["DEBUG"]);
define('NOTIFICATION', $env["NOTIFICATION"]);

if (DEBUG)
    ini_set('display_errors', '1');


require_once "../config/database.php";
require_once "../Library/Router.php";
require_once "../Library/Helper.php";

require_once "../Controllers/UserController.php";
require_once "../Controllers/PostController.php";
require_once "../Controllers/WidgetController.php";

// define routers
$router = new Router();

$router->get('/', function () {
    include "../Views/index.php";
    exit;
});
$router->get('/admin', function () {
    include "../Views/admin/index.html";
    exit;
});
$router->get('/admin/users', function () {
    include "../Views/admin/users.html";
    exit;
});
$router->get('/admin/posts', function () {
    include "../Views/admin/posts.html";
    exit;
});

// auth routes
$router->post('/api/login', [new UserController(), "login"]);
$router->post('/api/logout', [new UserController(), "logout"], ["auth"]);

// user routes
$router->post('/api/users', [new UserController(), "list"], ["auth:admin"]);
$router->post('/api/user/create', [new UserController(), "create"]);
$router->get('/api/user/view/{id}', [new UserController(), "view"], ["auth:admin"]);
$router->post('/api/user/delete/{id}', [new UserController(), "delete"], ["auth:admin"]);
$router->post('/api/user/approve', [new UserController(), "approve"], ["auth:admin"]);

// post routes
$router->post('/api/posts', [new PostController(), "list"], ["auth"]);
$router->post('/api/post/create', [new PostController(), "create"], ["auth:admin"]);
$router->get('/api/post/view/{id}', [new PostController(), "view"], ["auth"]);
$router->post('/api/post/update/{id}', [new PostController(), "update"], ["auth:admin"]);
$router->post('/api/post/delete/{id}', [new PostController(), "delete"], ["auth:admin"]);

// widget routes
$router->post('/api/widgets', [new WidgetController(), "list"]);
$router->post('/api/widget/create', [new WidgetController(), "create"], ["auth:admin"]);
$router->get('/api/widget/view/{id}', [new WidgetController(), "view"], ["auth"]);
$router->post('/api/widget/update/{id}', [new WidgetController(), "update"], ["auth:admin"]);
$router->post('/api/widget/delete/{id}', [new WidgetController(), "delete"], ["auth:admin"]);

$router->match();
