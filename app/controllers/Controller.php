<?php

namespace app\controllers;

class Controller
{

    protected function render($view, $params = [])
    {
        $params['isLoggedIn'] = $this->loggedIn();
        $content = $this->renderView($view, $params);
        return $this->renderView('layout/main', [
            'content' => $content,
            'flashMessages' => $this->getFlashMessages(true),
            'isLoggedIn' => $params['isLoggedIn']
        ]);
    }

    private function renderView($view, $params = [])
    {
        if (!$view) return;
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require "app/views/" . $view . ".php";
        return ob_get_clean();
    }

    protected function validate($params)
    {
        $cleanParams = [];
        foreach ($params as $key => $value) {
            if (!is_numeric($value) && empty(trim($value))) continue;
            $cleanParams[$key] = $value;
        }

        return $cleanParams;
    }

    public function redirect($uri)
    {
        if ($uri == 'back') {
            $uri = $_SERVER['HTTP_REFERER'] ?? '/';
        }
        header("Location: " . $uri);
        exit();
    }

    public function setSession($key, $value)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION[$key] = $value;
    }

    public function getSession($key, $default = null)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public function setFlashMessage($key, $value)
    {
        $flashes = $this->getSession('flashes', []);
        $flashes[] = ['key' => $key, 'value' => $value];
        $this->setSession('flashes', $flashes);
    }

    public function getFlashMessages($clear = false)
    {
        $flashes =  $this->getSession('flashes');

        if ($clear && $flashes) {
            unset($_SESSION['flashes']);
        }

        return $flashes;
    }

    public function authenticate()
    {
        $this->setSession('user', true);
        setcookie('user', 'login', time() + 3600);
    }

    public function loggedIn()
    {
        return $this->getSession('user');
    }

    public function signOut()
    {
        if ($this->getSession('user')) {
            unset($_SESSION['user']);
        }
    }
}
