<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest ()) {
            //current action is method of controller that have action on current router
            if (empty($this->actions) || in_array (Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();//
            }
        }
    }
}