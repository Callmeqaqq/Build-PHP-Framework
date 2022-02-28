<?php

namespace app\core;

use app\core\exception\ForbiddenException;
use app\core\exception\NotFoundException;
use app\core\middlewares\CheckLoginMiddleware;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routeMap = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback)
    {
        $this->routeMap['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routeMap['post'][$path] = $callback;
    }

    private function getCallBack()
    {
        $method = $this->request->getMethod ();
        $url = $this->request->getUrl ();
        $url = trim ($url, '/');
        $routes = $this->routeMap[$method] ?? [];
        $routeParams = false;
        //iterating register routes
        foreach ($routes as $route => $callback) {
            $route = trim ($route, '/');
            $routeParams = [];
            if (!$route) {
                continue;
            }
            //find all param's name from route and save it in $routeParams
            if (preg_match_all ('/\{(\w+)(:[^}]+)?}/', $route, $matchesResult)) {
                $routeParams = $matchesResult[1];
            }

            //change abc/{id:\d+} => abc/(\d)/(\w+)
            $routeRegex = "@^" .
                preg_replace_callback ('/\{\w+(:([^}]+))?}/',
                    fn($match) => isset($match[2]) ? "({$match[2]})" : '(\w+)',
                    $route)
                . "$@";
            //var_dump($routeRegex);
            if (preg_match_all ($routeRegex, $url, $valueMatches)) {
                //var_dump($valueMatches);[0] = full matches, [1] = first_matches, [2] = second_matches,...
                $values = [];
                for ($i = 1; $i < count ($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine ($routeParams, $values);
                $this->request->setRouteParams ($routeParams);
                return $callback;
            }
        }
        return false;
    }

    public function resolve()
    {
        $method = $this->request->getMethod ();
        $url = $this->request->getUrl ();
        $callback = $this->routeMap[$method][$url] ?? false;
        if ($callback === false) {
            $callback = $this->getCallBack ();
            if($callback === false){
                throw new NotFoundException();
            }
        }
        //This is_string may not be necessary
        if (is_string ($callback)) {
            return Application::$app->view->renderView ($callback);
        }
        if (is_array ($callback)) {
            Application::$app->controller = new $callback[0]();//specify what controller is being implemented
            Application::$app->controller->action = $callback[1];//for middleware
            $callback[0] = Application::$app->controller;
            foreach (Application::$app->controller->getMiddlewares () as $middleware) {
                $middleware->execute ($this->response);
            }
        }
        return call_user_func ($callback, $this->request, $this->response);
    }


}