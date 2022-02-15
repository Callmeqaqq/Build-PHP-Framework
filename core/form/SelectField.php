<?php

namespace app\core\form;

use app\core\Model;

abstract class SelectField
{
    public Model $model;
    public string $attribute;
    public array $param;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute, array $params)//get from any form's field
    {
        $this->model = $model;
        $this->attribute = $attribute;
        $this->param = $params;
    }

    abstract public function renderOption(): string;

    public function __toString(): string
    {
        //label of field is generic of core folder
        return sprintf ('
            <div class="mb-3">
                <label class="form-label">%s</label>
                <select name="%s" class="%s form-select">
                <option selected disabled>Choose...</option>
                    %s
                </select>
                <div class="invalid-feedback">
                    %s
                </div>
            </div>
        ', $this->model->getLabels ($this->attribute),
            $this->attribute,
            $this->model->hasError ($this->attribute) ? 'is-invalid' : '',
            $this->renderOption (),
            $this->model->getFirstError ($this->attribute),//get first error of $email properties of ContactForm
        );
    }
}