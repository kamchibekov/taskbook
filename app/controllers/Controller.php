<?php

namespace app\controllers;

class Controller
{

    protected function render($view, $params = [])
    {
        $content = $this->renderView($view, $params);
        return $this->renderView('layout/main', [
            'content' => $content,
            'flashMessages' => $this->getFlashMessages(true)
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
            if (empty(trim($value))) continue;
            $cleanParams[$key] = $value;
        }

        return $cleanParams;
    }

    public function redirect($uri)
    {
        header("Location: " . $uri);
        exit();
    }

    public function setFlashMessage($key, $value)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
        $flashes = isset($_SESSION['flashes']) ? $_SESSION['flashes'] : [];
        $flashes[] = ['key' => $key, 'value' => $value];
        $_SESSION['flashes'] = $flashes;
    }

    public function getFlashMessages($clear = false)
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $flashes = isset($_SESSION['flashes']) ? $_SESSION['flashes'] : null;

        if ($clear && $flashes) {
            unset($_SESSION['flashes']);
        }

        return $flashes;
    }
}
