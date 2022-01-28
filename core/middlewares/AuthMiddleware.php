<?php

namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;
use app\core\Response;

class AuthMiddleware extends BaseMiddleware
{
    public array $actions = [];

    /**
     * @param array $actions
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;//this = action on register argument of controller
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(Response $response)
    {
        $actionURL = Application::$app->controller->action;
        if (Application::isGuest ()) {
            //current action is method of controller that have action on current router
            if (empty($this->actions) || in_array ($actionURL, $this->actions)) {
                throw new ForbiddenException();//
            }
        }
    }
}