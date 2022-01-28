<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;
use app\core\Response;

class LoginMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;//this = action on register argument register
    }

    public function execute(Response $response)
    {
        if (Application::isGuest ()) {
            $response->redirect ('/login');
            exit;
        }
    }
}