<?php

namespace app\core\form;

use app\core\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_EMAIL = 'email';
    public const TYPE_NUMBER = 'number';
    public const TYPE_DATE = 'date';
    public const TYPE_TIME = 'time';

    public string $type;
    public string $inputAttribute = '';

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute, string $inputAttribute)
    {
        $this->type = self::TYPE_TEXT;
        $this->inputAttribute = $inputAttribute;
        parent::__construct ($model, $attribute);
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function emailField()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }

    public function numberField()
    {
        $this->type = self::TYPE_NUMBER;
        return $this;
    }

    public function dateField()
    {
        $this->type = self::TYPE_DATE;
        return $this;
    }

    public function timeField()
    {
        $this->type = self::TYPE_TIME;
        return $this;
    }

    public function setAttribute($attribute, $value){
        $this->inputAttribute .= $attribute.'="'.$value.'"';
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf ('<input %s type="%s" name="%s" value="%s" class="form-control %s">',
            $this->inputAttribute ? $this->inputAttribute : '',
            $this->type,
            $this->attribute,// ex: this attribute is email
            $this->model->{$this->attribute},//accessing email value, value get from properties '$email' in ContactForm
            $this->model->hasError ($this->attribute) ? 'is-invalid' : '',
        );
    }
}