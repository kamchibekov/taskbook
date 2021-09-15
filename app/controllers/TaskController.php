<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Task;

class TaskController extends Controller
{
    public function index($params)
    {
        $page = $params['page'] ?? 1;
        if ($page < 1) $page = 1;
        $limit = 3;

        $offset = ($page - 1) * $limit;

        $sort = isset($params['sort']) && is_array($params['sort']) ? $params['sort'] : '';

        $sortFields = [
            'user_name' => 'asc',
            'email' => 'asc',
            'status' => 'asc'
        ];

        $sortQuery = '';

        foreach (array_keys($sortFields) as $key) {
            if (isset($sort[$key])) {
                $sortOrder = strtolower($sort[$key]) == 'desc' ? 'desc' : 'asc';
                $sortFields[$key] = $sortOrder  == 'desc' ? 'asc' : 'desc';
                $sort = $key . ' ' . $sortOrder;
                $sortQuery = "&sort[{$key}]={$sortOrder}";
                break;
            }
        }

        $tasks = Task::findAll([
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort,
        ]);


        return $this->render('task/index', [
            'count' => Task::getCount(),
            'tasks' => $tasks,
            'page' => $page,
            'limit' => $limit,
            'sort' => $sortFields,
            'sortQuery' => $sortQuery
        ]);
    }

    public function createTask($params)
    {

        $params = $this->validate($params);
        $params['status'] = 0;
        $params['content_changed'] = 0;

        $task = new Task();
        if ($task->load($params) && $task->save()) {
            $this->setFlashMessage('success', 'Task successfully created!');
        } else {
            $this->setFlashMessage('danger', 'Not enough data to create a task!');
        }

        return $this->redirect('/');
    }

    public function change($params)
    {
        $params = $this->validate($params);
        $task_id = $params['task_id'] ?? null;
        $status = $params['status'] ?? null;
        $text = $params['text'] ?? null;

        if (!$this->loggedIn()) {
            $this->setFlashMessage('danger', 'You are not logged in');
            $this->redirect('/');
        }

        if (!is_numeric($task_id)) {
            return json_encode(['error' => 'Task was not found']);
        }

        $fields = [];

        if (is_numeric($status)) {
            $fields['status'] = $status;
        }

        if ($text) {
            $fields['task_text'] = $text;
            $fields['content_changed'] = 1;
        }

        $task = Task::update($task_id, $fields);

        return json_encode(['status' => $task]);
    }
}
