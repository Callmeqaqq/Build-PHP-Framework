<?php

use app\controllers\ApiController;
use app\controllers\AuthController;
use app\controllers\ProjectController;
use app\controllers\SiteController;
use app\controllers\TaskController;
use app\core\Application;
use app\core\database\QueryBuilder;
use app\models\User;

require_once __DIR__ . '/../vendor/autoload.php';

//ENV
$dotenv = Dotenv\Dotenv::createImmutable (dirname (__DIR__));
$dotenv->load ();
$config = [
    'User()' => User::class,//call user class
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];

$app = new Application(dirname (__DIR__), $config);

//route
//==========================API==============================
$app->router->post ('/list-user-api', [ApiController::class, 'getListUser']);
$app->router->post ('/list-valid-user-api', [ApiController::class, 'getValidListUser']);
$app->router->post ('/set-leader', [ApiController::class, 'setLeaderOfProject']);
//==========================Site==============================
$app->router->get ('/', [SiteController::class, 'home']);
$app->router->get ('/contact', [SiteController::class, 'contact']);
$app->router->post ('/contact', [SiteController::class, 'contact']);
//==========================Task==============================
$app->router->get ('/get-all-task/{id}', [TaskController::class, 'getTaskInProject']);
$app->router->post ('/create-new-task', [TaskController::class, 'createNewTask']);
$app->router->post ('/move-task-status', [TaskController::class, 'updateStatus']);
$app->router->post ('/update-task-info', [TaskController::class, 'updateTaskInfo']);
$app->router->get ('/get-task-comments/{id}', [TaskController::class, 'getCommentOfTask']);
$app->router->post ('/check-user-unique-in-task', [TaskController::class, 'checkUniqueUserInTask']);
$app->router->post ('/add-comment', [TaskController::class, 'addCommentToTask']);
$app->router->get ('/get-task-statistical/{id}', [TaskController::class, 'getTaskStatistical']);
//==========================Project==============================
$app->router->get ('/new-project', [ProjectController::class, 'createProject']);
$app->router->post ('/new-project', [ProjectController::class, 'createProject']);
$app->router->get ('/list-project', [ProjectController::class, 'listProject']);
$app->router->get ('/project', [ProjectController::class, 'project']);
$app->router->post ('/project', [ProjectController::class, 'project']);
//=======================AUTHENTICATION=========================
$app->router->get ('/login', [AuthController::class, 'login']);
$app->router->post ('/login', [AuthController::class, 'login']);
$app->router->get ('/profile', [SiteController::class, 'profile']);
$app->router->get ('/register', [AuthController::class, 'register']);
$app->router->post ('/register', [AuthController::class, 'register']);
$app->router->get ('/logout', [AuthController::class, 'logout']);

$app->run ();