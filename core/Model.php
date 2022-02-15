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

    public function loadData($data)//from getBody
    {
        foreach ($data as $key => $value) {
            if (property_exists ($this, $key)) {//$this -> properties in the Model that call loadData
                $this->{$key} = $value;//this->properties = input value
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

    public function validate()//this will be call after loadData
    {
        foreach ($this->rules () as $attribute => $rules) {//$rules will be declared in child class, so it won't be abstract anymore
            $value = $this->{$attribute};//ex: 'email' have value = [self::RULE_REQUIRED, self::RULE_EMAIL], we handle each attribute //attribute = name of input
            foreach ($rules as $rule) {//ex: get "self::RULE_REQUIRED inside [self::RULE_REQUIRED, self::RULE_EMAIL]
                $ruleName = $rule;
                if (!is_string ($ruleName)) {
                    $ruleName = $rule[0];//ex: get "self::RULE_MAX" inside [self::RULE_MAX, 'max' => 100]
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {//properties have this RULE and value input not exist
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
        return empty($this->errors);//if no errors[], this return true
    }

    private function addErrorForRule(string $attribute, string $rule, $param = [])//attribute = name of input
    {
        $message = $this->errorMessages ()[$rule] ?? '';//if $rule is self::RULE_REQUIRED, $message = This field is required
        foreach ($param as $key => $value) {//ex: $key = get 'min' inside [self::RULE_MIN, 'min' => 5]
            $message = str_replace ("{{$key}}", $value, $message);//replace {min} inside '... at least {min}' = 5 inside [self::RULE_MIN, 'min' => 5]
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)//attribute = name of input
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

    public function hasError($attribute)//attribute = name of input
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)//attribute = name of input
    {
        return $this->errors[$attribute][0] ?? false;
    }

    //query builder

}