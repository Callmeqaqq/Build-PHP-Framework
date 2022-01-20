<?php

class m0002_insert_password_column
{
    public function up()
    {
        $db = \app\core\Application::$app->db;
        $query = "ALTER TABLE users
                    ADD password VARCHAR(255) NOT NULL;";
        $db->pdo->exec ($query);
    }

    public function down()
    {
        $query = "ALTER TABLE users DROP COLUMN password";
    }
}