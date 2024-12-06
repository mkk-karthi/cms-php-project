<?php
ini_set('display_errors', '1');

require_once "../config/database.php";
require_once "../Library/Router.php";
require_once "../Library/Helper.php";

require_once "../Controllers/UserController.php";
require_once "../Controllers/PostController.php";

// start the session
session_start();

define('APP_PATH', str_replace("/public", "", $_SERVER['DOCUMENT_ROOT']));
define('PUPLIC_PATH', $_SERVER['DOCUMENT_ROOT']);

// get env variables
$env = parse_ini_file('../.env');
define('APP_NAME', $env["APP_NAME"]);

// define routers
$router = new Router();

$router->get('/', function () {
    include "../Views/index.html";
    exit;
});


$router->get('api/mail', function () {
    $mail_data = [
        "subject" => "Here is the subject",
        "content" => "This is the HTML message body <b>in bold!</b>",
        "to" => "mkk2001vnr@gmail.com"
    ];
    Helper::notification($mail_data);
    echo "sented!";
    exit;
});

// auth routes
$router->post('/api/login', [new UserController(), "login"]);
$router->post('/api/logout', [new UserController(), "logout"], ["auth"]);

// user routes
$router->post('/api/users', [new UserController(), "list"], ["auth:admin"]);
$router->post('/api/user/create', [new UserController(), "create"]);
$router->get('/api/user/view/{id}', [new UserController(), "view"], ["auth:admin"]);
$router->post('/api/user/update/{id}', [new UserController(), "update"]);
$router->post('/api/user/delete/{id}', [new UserController(), "delete"], ["auth:admin"]);
$router->post('/api/user/approve', [new UserController(), "approve"], ["auth:admin"]);

// user routes
$router->post('/api/posts', [new PostController(), "list"], ["auth:admin"]);
$router->post('/api/post/create', [new PostController(), "create"]);
$router->get('/api/post/view/{id}', [new PostController(), "view"], ["auth:admin"]);
$router->post('/api/post/update/{id}', [new PostController(), "update"]);
$router->post('/api/post/delete/{id}', [new PostController(), "delete"], ["auth:admin"]);

$router->match();
