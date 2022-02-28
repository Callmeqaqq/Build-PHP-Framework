<?php

namespace app\core;

class  Request
{
    private array $routeParams = [];

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        //get position from [0] of PATH to question mask
        $position = strpos($path, '?');
        //if there's not have question mask
        if ($position === false) {
            return $path;//return '/'
        }
        //return string form [0] of PATH to position just get before
        return substr($path, 0, $position);
    }

    public function urlQuery()
    {
        $param = $_SERVER['QUERY_STRING'] ?? null;
        parse_str($param, $get_array);
        return $get_array;
    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function getBody()//return submitted data from form
    {
        $body = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                //This FILTER_SANITIZE_SPECIAL_CHARS filter is used to escape "<>& and characters with ASCII
            }
        }
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    public function setRouteParams(array $routeParams): static
    {
        $this->routeParams = $routeParams;
        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }
}