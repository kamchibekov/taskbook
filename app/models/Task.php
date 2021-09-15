<?php

namespace app\models;

use app\config\Db;

class task
{
    public $id;
    public $user_name;
    public $email;
    public $task_text;
    public $status;
    public $content_changed;

    private static $attributes = null;

    public function __construct()
    {
    }

    public static function tableName()
    {
        return 'tasks';
    }

    public static function getCount()
    {
        $db = Db::getInstance()->db();
        $tableName = self::tableName();
        $count = $db->query("SELECT COUNT(*) as count FROM {$tableName}")->fetchColumn();
        return $count;
    }

    public static function update(int $id, array $fields)
    {
        $db = Db::getInstance()->db();
        $tableName = self::tableName();

        $SET = '';

        foreach (array_keys($fields) as $field) {
            $SET .= "$field=:{$field},";
        }

        $SET = rtrim($SET, ',');

        $sql = "UPDATE {$tableName} SET $SET WHERE id ={$id}";

        $stmt = $db->prepare($sql);

        foreach ($fields as $key => $value) {
            $type = (is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
            error_log("TYPE: " . $type);
            $stmt->bindValue(":{$key}", $value);
        }

        $result = $stmt->execute();

        if ($result) {
            return $stmt->rowCount();
        }
        return false;
    }

    public static function findAll($params)
    {
        $offset = $params['offset'] ?? 0;
        $limit = $params['limit'] ?? 0;
        $fields = $params['fields'] ?? '*';
        $sort = !empty($params['sort']) ? 'ORDER BY ' . $params['sort'] : '';

        $tableName = self::tableName();
        $sql = "SELECT {$fields} FROM {$tableName} {$sort} limit :offset,:lim";
        $db = Db::getInstance()->db();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindParam(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $tasks = [];
        foreach ($result as $row) {
            $taskObject = new \stdClass();
            foreach ($row as $key => $value) {
                $taskObject->{$key} = htmlspecialchars($value);
            }
            $tasks[] = $taskObject;
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
