<?php

namespace app\core;

use app\core\database\Database;
use app\core\database\DatabaseModel;

class Application
{
    public static string $ROOT_DIR;
    public string $callUserClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public static Application $app;
    public Controller $controller;
    public ?DatabaseModel $user;//question mark that mean $user might be null as guest, not login
    public View $view;
    public string $userId;
    public string $access;

    public function __construct($rootPath, array $config)
    {
        $this->callUserClass = $config['User()'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get ('user');
        if ($primaryValue) {
            $primaryKey = $this->callUserClass::primaryKey ();
            $this->user = $this->callUserClass::findOne ([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public function run()
    {
        try {
            echo $this->router->resolve ();
        } catch (\Exception $e) {
            $this->response->setStatusCode ($e->getCode ());
            $this->controller = new Controller();
            $this->controller->setLayout ('_error');
            echo $this->view->renderView ('_404', [
                'exception' => $e
            ]);
        }
    }

    public static function isGuest(): bool
    {
        return !self::$app->user;//?DatabaseModel $user doesn't exist, return true;
    }

    public function access($access)
    {
        $this->access = $access;
        return true;
    }


    public function login(DatabaseModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey ();
        $primaryValue = $user->{$primaryKey};
        $this->session->set ('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove ('user');
    }

//    /**
//     * @return Controller
//     */
//    public function getController(): Controller
//    {
//        return $this->controller;
//    }
//
//    /**
//     * @param Controller $controller
//     */
//    public function setController(Controller $controller): void
//    {
//        $this->controller = $controller;
//    }

}