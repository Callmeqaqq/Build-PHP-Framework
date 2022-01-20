<?php

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public function loadData($data)
    {

        foreach ($data as $key => $value) {
            if (property_exists ($this, $key)) {
                $this->{$key} = $value;
                //string(6) "name"
                //string(11) "asd@asd.com"
                //string(6) "pass"
                //string(6) "confirm_pass"
            }
        }
    }

    public function labels(): array
    {
        return [];
    }

    public function getLabels($attribute)//ex:'email' => 'Your Email', email is $attribute
    {
        return $this->labels ()[$attribute] ?? $attribute;
    }

    abstract public function rules(): array;//Model file will return array

    public array $errors = [];

    public function validate()
    {
        foreach ($this->rules () as $attribute => $rules) {
            $value = $this->{$attribute};//ex: email have value = [self::RULE_REQUIRED, self::RULE_EMAIL]
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string ($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule ($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var ($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule ($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen ($value) < $rule['min']) {
                    $this->addErrorForRule ($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen ($value) > $rule['max']) {
                    $this->addErrorForRule ($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabels ($rule['match']);
                    $this->addErrorForRule ($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];//classname
                    $uniqueAttr = $rule['attribute'] ?? $attribute;//column in database and index key of array rule();
                    $tableName = $className::tableName ();//ex: User::tableName(), it mean table users are selected
                    $stmt = Application::$app->db->prepare ("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                    $stmt->bindValue (":attr", $value);//value = input value
                    $stmt->execute ();
                    $record = $stmt->fetch ();
                    if ($record) {
                        $this->addErrorForRule ($attribute, self::RULE_UNIQUE, ['field' => $this->getLabels ($attribute )]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    private function addErrorForRule(string $attribute, string $rule, $param = [])
    {
        $message = $this->errorMessages ()[$rule] ?? '';//if $rule is self::RULE_REQUIRED, $message = This field is required
        foreach ($param as $key => $value) {
            $message = str_replace ("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'Min length must be at least {min}',
            self::RULE_MAX => 'Max length must be at least {max}',
            self::RULE_MATCH => 'This field must the same as {match}',
            self::RULE_UNIQUE => 'This {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}