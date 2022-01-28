<?php

namespace app\core\middlewares;

use app\core\Response;

abstract class BaseMiddleware
{
    abstract public function execute(Response $response);
}