<?php

namespace app\core\form;

use app\core\Model;

class OptionField extends BaseField
{
    public function __construct(Model $model, string $attribute)
    {
        parent::__construct ($model, $attribute);
    }

    public function renderInput(): string
    {
       return false;
    }
}