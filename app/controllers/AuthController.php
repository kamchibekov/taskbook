<?php

namespace app\controllers;

use app\controllers\Controller;

class AuthController extends Controller
{
    public function login($params)
    {
        $params = $this->validate($params);
        $useranme = $params['username'] ?? null;
        $password = $params['password'] ?? null;

        if (!$useranme || !$password) {
            $this->setFlashMessage('danger', 'Username and password required.');
            $this->redirect('/');
        }

        $config = require CONF_DIR . "/config.php";

        if ($config['admin']['username'] != $useranme) {
            $this->setFlashMessage('danger', 'Username not found.');
            $this->redirect('/');
        }

        if ($password != $config['admin']['password']) {
            $this->setFlashMessage('danger', 'Password incorrect.');
            $this->redirect('/');
        }

        $this->authenticate();
        $this->redirect('back');
    }

    public function logout()
    {
        $this->signOut();
        $this->redirect('/');
    }
}
