<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Task;

class TaskController extends Controller
{
    public function index($params)
    {
        $page = isset($params['page']) ? intval($params['page']) : 1;
        if ($page < 1) $page = 1;
        $limit = 3;

        $offset = ($page - 1) * $limit;

        $tasks = Task::findAll($offset, $limit);
        return $this->render('task/index', [
            'count' => Task::getCount(),
            'tasks' => $tasks,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    public function createTask($params)
    {

        $params = $this->validate($params);
        $params['done'] = 0;
        $params['content_changed'] = 0;

        $task = new Task();
        if ($task->load($params) && $task->save()) {
            $this->setFlashMessage('success', 'Task successfully created!');
        } else {
            $this->setFlashMessage('danger', 'Not enough data to create a task!');
        }

        return $this->redirect('/');
    }
}
