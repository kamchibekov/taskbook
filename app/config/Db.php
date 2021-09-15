<?php

namespace app\config;

class Db
{
    private static $instance = null;
    private $db = null;

    private function __construct()
    {
        $config = require "config.php";
        try {
            $this->db = new \PDO('sqlite:' . __DIR__ . '/' . $config['db']);
        } catch (\PDOException $e) {
            die('DB connection error. message: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public function db()
    {
        return $this->db;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
