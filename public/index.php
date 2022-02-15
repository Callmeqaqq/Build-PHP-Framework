<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Application;
use app\core\database\QueryBuilder;



require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable (dirname (__DIR__));
$dotenv->load ();

$config = [
    'User()' => \app\models\User::class,//call user class
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname (__DIR__), $config);

//route
//==========================Site==============================
$app->router->get ('/', [SiteController::class, 'home']);
$app->router->get ('/contact', [SiteController::class, 'contact']);
$app->router->post ('/contact', [SiteController::class, 'contact']);

//=====================AUTHENTICATION=========================
$app->router->get ('/login', [AuthController::class, 'login']);
$app->router->post ('/login', [AuthController::class, 'login']);
$app->router->get ('/profile', [SiteController::class, 'profile']);
$app->router->get ('/register', [AuthController::class, 'register']);
$app->router->post ('/register', [AuthController::class, 'register']);
$app->router->get ('/logout', [AuthController::class, 'logout']);

$app->run ();
