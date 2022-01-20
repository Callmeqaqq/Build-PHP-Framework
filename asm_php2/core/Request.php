<?php

namespace app\core;

class  Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        //get position from [0] of PATH to question mask
        $position = strpos($path, '?');
        //if there's not have question mask
        if ($position === false) {
            return $path;//return '/'
        }
        //return string form [0] of PATH to position just get before
        $result = substr($path, 0, $position);
        echo '<pre>';
        var_dump($result);
        echo'</pre>';
        exit;
        return $result;
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function getBody()
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                //This FILTER_SANITIZE_SPECIAL_CHARS filter is used to escape "<>& and characters with ASCII
            }
        }
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}