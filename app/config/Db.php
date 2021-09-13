<?php

namespace app\config;

class Db
{
    private static $instance = null;
    private $db = null;

    private function __construct()
    {
        $config = require "config.php";
        $this->db = new \SQLite3(__DIR__ . '/' . $config['db']);
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
