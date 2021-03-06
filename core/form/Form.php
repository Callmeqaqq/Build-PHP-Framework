<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf ('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function input(Model $model, $attribute, $inputAttribute = '')
    {
        return new InputField($model, $attribute, $inputAttribute);
    }

    public function textarea(Model $model, $attribute)
    {
        return new TextareaField($model, $attribute);
    }

    public function select(Model $model, $attribute, $optionsTag)
    {
        return new OptionField($model, $attribute, $optionsTag);
    }
}