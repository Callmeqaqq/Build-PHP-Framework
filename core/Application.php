<?php

namespace app\core;

use app\core\database\Database;
use app\core\database\DatabaseModel;

class Application
{
    public static string $ROOT_DIR;

    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public static Application $app;
    public Controller $controller;
    public ?DatabaseModel $user;//question mark that mean $user might be null as guest, not login
    public View $view;

    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];//'userClass' => \app\models\User::class,
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $this->user = new $this->userClass;//$this->user now can call method in DBModel
        $primaryValue = $this->session->get ('user');
        if ($primaryValue) {
            $primaryKey = $this->user->primaryKey ();
            $this->user = $this->user->findOne ([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }

    public static function isGuest(): bool
    {
        return !self::$app->user;//user doesn't exist
    }

    public function run()
    {
        try {
            echo $this->router->resolve ();
        } catch (\Exception $e) {
            $this->response->setStatusCode ($e->getCode ());
            echo $this->view->renderView ('_404', [
                'exception' => $e
            ]);
        }
    }

    public function login(DatabaseModel $user)
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