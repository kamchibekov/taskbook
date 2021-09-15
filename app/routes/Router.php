<?php

namespace app\routes;

class Router
{
    private static $routes = array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    public static function add($expression, $handler, $method = 'get')
    {
        array_push(self::$routes, array(
            'expression' => $expression,
            'handler' => $handler,
            'method' => $method
        ));
    }

    public static function pathNotFound($function)
    {
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function)
    {
        self::$methodNotAllowed = $function;
    }

    public static function run($basepath = '', $case_matters = false, $trailing_slash_matters = false, $multimatch = false)
    {

        $basepath = rtrim($basepath, '/');

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        $path = '/';

        if (isset($parsed_url['path'])) {
            if ($trailing_slash_matters) {
                $path = $parsed_url['path'];
            } else {
                if ($basepath . '/' != $parsed_url['path']) {
                    $path = rtrim($parsed_url['path'], '/');
                } else {
                    $path = $parsed_url['path'];
                }
            }
        }

        $path = urldecode($path);

        $method = $_SERVER['REQUEST_METHOD'];

        $path_match_found = false;

        $route_match_found = false;

        foreach (self::$routes as $route) {

            if ($basepath != '' && $basepath != '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }

            $route['expression'] = '^' . $route['expression'];

            $route['expression'] = $route['expression'] . '$';

            if (preg_match('#' . $route['expression'] . '#' . ($case_matters ? '' : 'i') . 'u', $path, $matches)) {
                $path_match_found = true;

                foreach ((array)$route['method'] as $allowedMethod) {
                    if (strtolower($method) == strtolower($allowedMethod)) {
                        array_shift($matches); 

                        if ($basepath != '' && $basepath != '/') {
                            array_shift($matches);
                        }

                        if ($_REQUEST) {
                            $matches = array_merge($_REQUEST, $matches);
                        }

                        if (strpos($route['handler'], '/') !== false) {
                            [$class, $method] = explode('/', $route['handler']);
                            $class = 'app\controllers\\' . $class;

                            if (class_exists($class) && method_exists($class, $method)) {
                                if ($return_value = (new $class())->$method($matches)) {
                                    echo $return_value;
                                }
                            } else {
                                die("Controller or action doesn't exist.");
                            }
                        } else if ($return_value = call_user_func_array($route['handler'], $matches)) {
                            echo $return_value;
                        }

                        $route_match_found = true;

                        break;
                    }
                }
            }

            if ($route_match_found && !$multimatch) {
                break;
            }
        }

        if (!$route_match_found) {
            if ($path_match_found) {
                if (self::$methodNotAllowed) {
                    call_user_func_array(self::$methodNotAllowed, array($path, $method));
                }
            } else {
                if (self::$pathNotFound) {
                    call_user_func_array(self::$pathNotFound, array($path));
                }
            }
        }
    }
}
