<?php

class m0001_make_users_table
{
    public function up()
    {
        $db = \app\core\Application::$app->db;
        $query = "CREATE TABLE users(
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(255) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    status TINYINT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB;";
        $db->pdo->exec ($query);
    }

    public function down()
    {
        $query = "DROP TABLE users;";
    }
}