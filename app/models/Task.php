<?php

namespace app\models;

use app\config\Db;

class task
{
    public $id;
    public $user_name;
    public $email;
    public $task_text;
    public $done;
    public $content_changed;

    private static $attributes = null;

    public static function tableName()
    {
        return 'tasks';
    }

    public static function getCount()
    {
        $db = Db::getInstance()->db();
        $tableName = self::tableName();
        $count = $db->querySingle("SELECT COUNT(*) as count FROM {$tableName}");
        return $count;
    }

    public static function findAll($offset = 0, $limit = 3)
    {
        $db = Db::getInstance()->db();
        $tableName = self::tableName();
        $stmt = $db->prepare("SELECT * FROM {$tableName} limit :offset,:lim");
        $stmt->bindParam(':offset', $offset);
        $stmt->bindParam(':lim', $limit);
        $result = $stmt->execute();
        $tasks = [];
        while ($task = $result->fetchArray()) {
            $tasks[] = (object)$task;
        }
        return $tasks;
    }

    private function getAttributes()
    {
        if (!self::$attributes) {
            $attributes = get_object_vars($this);
            unset($attributes['id']);
            self::$attributes = array_keys($attributes);
        }

        return self::$attributes;
    }

    public function load($params = [])
    {
        $safe = true;

        foreach ($this->getAttributes() as $name) {
            if (isset($params[$name])) {
                $this->$name = $params[$name];
            } else {
                $safe = false;
                break;
            }
        }
        return $safe;
    }

    public function save()
    {
        $db = Db::getInstance()->db();

        $fields = implode(', ', $this->getAttributes());
        $values = implode(',:', $this->getAttributes());
        $tableName = self::tableName();
        $sql = "INSERT INTO {$tableName} ({$fields}) VALUES (:{$values})";
        $stmt = $db->prepare($sql);
        foreach ($this->getAttributes() as $name) {
            $stmt->bindValue(":$name", $this->$name);
        }
        return $stmt->execute();
    }
}
