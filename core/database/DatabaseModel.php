<?php

namespace app\core\database;

use app\core\Application;
use app\core\Model;

abstract class DatabaseModel extends Model
{
    abstract public function tableName(): string;

    abstract public function attributes(): array;//column_name

    abstract public function primaryKey(): string;

    public function save()//insert
    {
        $tableName = $this->tableName ();
        $attributes = $this->attributes ();
        $params = array_map (fn($attr) => ":$attr", $attributes);
        $stmt = self::prepare ("INSERT INTO $tableName (" . implode (',', $attributes) . ") 
                    VALUES (" . implode (',', $params) . ")");

        //var_dump($stmt, $params, $attributes);
        foreach ($attributes as $attribute) {
            $stmt->bindValue (":$attribute", $this->{$attribute});
        }
        $stmt->execute ();
        return true;
    }

    public function findOne($where)//where look like: [email => quang@domain.com, name => Quang]
    {
        $tableName = static::tableName ();//static corresponds to an actual class on which the findOne() will be called
        $attribute = array_keys($where);
        //select * from $tableName where email = :email, name = :name
        //we have 2 key of array $where, so we need to combine them with AND
        $sql =  implode("AND " ,array_map(fn($attr) => "$attr = :$attr", $attribute));//if $attribute is email, this callback will return :email
        //select * from $tableName where $sql
        $stmt = self::prepare ("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {//$key = email, $item = quang@domain.com
            $stmt->bindValue (":$key", $item);
        }
        $stmt->execute ();
        //specify the className
        //fetchObject return object by default, but we want this fetchObject return an instance of the class on which findOne() is called
        return $stmt->fetchObject (static::class);
    }

    public function getAll(){

    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare ($sql);
    }


}