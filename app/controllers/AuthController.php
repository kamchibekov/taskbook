<?php

namespace app\controllers;

use app\controllers\Controller;

class AuthController extends Controller
{
    public function login($params) {
        print_r($params);
        die;
        return $params;
    }
}
