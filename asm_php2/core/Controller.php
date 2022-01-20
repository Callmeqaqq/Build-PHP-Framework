<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    //default layout
    public string $layout = 'main';
    public string $action = '';

    /**
     * @var BaseMiddleware[];
     */
    protected array $middlewares = [];

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView ($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}