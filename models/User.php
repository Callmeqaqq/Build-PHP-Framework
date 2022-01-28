<?php

namespace app\models;

use app\core\UserSpecificModel;

class User extends UserSpecificModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    const ROLE_STUDENT = 1;
    const ROLE_MENTOR = 2;

    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public string $name = '';
    public int $status = self::STATUS_INACTIVE;

    //name of table on database, for DatabaseModel
    public static function tableName(): string
    {
        return 'users';
    }

    //attributes for DatabaseModel
    public static function attributes(): array
    {
        return [
            'email', 'name', 'status', 'password',//because id is auto increase, so we don't need to implement here
        ];
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    //action to database
    public function save()//register
    {
        //handle what we're going to insert into database
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash ($this->password, PASSWORD_DEFAULT);
        return parent::save ();
    }

    //set rules for core Model handle it
    public function rules(): array
    {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'name' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5], [self::RULE_MAX, 'max' => 100]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 6]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    //override form Model to set friendly label in Field
    public function labels(): array
    {
        return [
            'email' => 'Your Email',
            'name' => 'Your Name',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password',
        ];
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }
}