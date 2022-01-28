<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{
    public function __construct()
    {
//        $this->checkLoginMiddleware ();
        $this->registerMiddleware (new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if ($request->isPost ()) {
            $loginForm->loadData ($request->getBody ());
            if ($loginForm->validate () && $loginForm->login ()) {
                Application::$app->session->setFlash ('success', 'Login success!');
                $response->redirect ('/');
                exit;
            }
        }
        $this->setLayout ('main2');
        return $this->render ('login', [
            'model' => $loginForm
        ]);
    }

    public function logout(Request $request, Response $response)
    {
        Application::$app->logout ();
        $response->redirect ('/');
    }

    public function register(Request $request)
    {
        $this->setLayout ('main2');
        $user = new User();
        if ($request->isPost ()) {
            $user->loadData ($request->getBody ());//load data first
            //next validation then execute on database
            if ($user->validate () && $user->save ()) {//if true
                Application::$app->session->setFlash ('success', 'Thanks for registering!');
                Application::$app->response->redirect ('/login');
                exit;
            }
            return $this->render ('register', [
                'model' => $user
            ]);
        }
        return $this->render ('register', [
            'model' => $user
        ]);
    }

    public function profile()
    {
        return $this->render ('profile');
    }
}