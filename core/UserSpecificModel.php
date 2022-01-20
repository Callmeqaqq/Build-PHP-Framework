<?php

namespace app\core;

use app\core\database\DatabaseModel;

abstract class UserSpecificModel extends DatabaseModel
{
    abstract public function getDisplayName(): string;
}