<?php

namespace app\core\database;

use app\core\Application;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute (\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable ();
        $appliedMigrations = $this->getAppliedMigrations ();//get from database

        $newMigrations = [];
        $files = scandir (Application::$ROOT_DIR . '/migrations');//get from local
        $toApplyMigrations = array_diff ($files, $appliedMigrations);//files that not exit in database
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;//array of $file sometime return '.' & '..' file that useless
            }
            require_once Application::$ROOT_DIR . '/migrations/' . $migration;

            $className = pathinfo ($migration, PATHINFO_FILENAME);//class = file's name without .php
            $instance = new $className();
            $this->log ("Applying migration $migration");
            $instance->up ();
            $this->log ("Applied migration $migration");
            $newMigrations[] = $migration;//push each class(file's name) to array
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations ($newMigrations);
        } else {
            $this->log ("All migration are allied");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec ("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migartion varchar(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) 
            ENGINE=INNODB;");
    }

    private function getAppliedMigrations()
    {
        $stmt = $this->pdo->prepare ("SELECT migration FROM migrations");
        $stmt->execute ();
        return $stmt->fetchAll (\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $newMigrations)
    {
        $str = implode (",", array_map (fn($m) => "('$m')", $newMigrations));
        $stmt = $this->pdo->prepare ("INSERT INTO migrations(migration) VALUES 
            $str
            ");
        $stmt->execute ();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '[' . date ('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}