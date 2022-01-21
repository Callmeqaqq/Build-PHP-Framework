<?php

namespace app\core\form;

use app\core\Model;

class OptionField extends SelectField
{
    public function __construct(Model $model, string $attribute, array $params)
    {
        parent::__construct ($model, $attribute, $params);

    }

    public function renderOption(): string
    {
        $optionsList = '';
        $inputValue = $this->model->{$this->attribute};
        foreach ($this->param as $option => $value) {
            $inputValue == $value ? $selected = 'selected' : $selected = '';
            $optionsList .= sprintf ('<option %s value="%s">%s</option>',
                $selected,
                $value,
                $option
            );
        }
        return $optionsList;
    }
}