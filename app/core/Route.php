<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 5:55
 */

namespace App\Core;

use App\Core\Contracts\RouteContract;

class Route implements RouteContract
{
    private static $routes = [];
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    public static function add($expression, $function, $method = 'get', $name = '')
    {
        $route = [
            'expression' => $expression,
            'function' => $function,
            'method' => $method,
            'type' => 'text'
        ];

        if (\is_callable($function)) {
            $route['type'] = 'callable';
        } else if (self::is_controller($function)) {
            $route['type'] = 'controller';
        }

        if (strlen($name) > 0) {
            $found = false;
            foreach (self::$routes as $rt) {
                if (isset($rt['name']) && $rt['name'] === $name) {
                    $found = true;
                    break;
                }
            }
            if (!$found && preg_match('/^[a-zA-Z0-9-_.]+$/', $name))
                $route['name'] = $name;
        }
        self::$routes[] = $route;
    }

    public static function get($expression, $function, $name = '')
    {
        self::add($expression, $function, 'get', $name);
    }

    public static function post($expression, $function, $name = '')
    {
        self::add($expression, $function, 'post', $name);
    }

    public static function pathNotFound($function)
    {
        self::$pathNotFound = $function;
    }

    public static function methodNotAllowed($function)
    {
        self::$methodNotAllowed = $function;
    }

    public static function run($basepath = '/', $case_matters = false, $trailing_slash_matters = false)
    {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        if (isset($parsed_url['path']) && $parsed_url['path'] !== '/') {
            if ($trailing_slash_matters) {
                $path = $parsed_url['path'];
            } else {
                $path = rtrim($parsed_url['path'], '/');
            }
        } else {
            $path = '/';
        }
        $method = $_SERVER['REQUEST_METHOD'];
        $path_match_found = false;
        $route_match_found = false;
        foreach (self::$routes as $route) {
            if ($basepath != '' && $basepath !== '/') {
                $route['expression'] = '(' . $basepath . ')' . $route['expression'];
            }
            $route['expression'] = '^' . $route['expression'];
            $route['expression'] .= '$';
            if (preg_match('#' . $route['expression'] . '#' . ($case_matters ? '' : 'i'), $path, $matches)) {
                $path_match_found = true;
                if (strtolower($method) == strtolower($route['method'])) {
                    array_shift($matches);
                    if ($basepath != '' && $basepath !== '/') {
                        array_shift($matches);
                    }
                    $res = '';
                    if ($route['type'] === 'callable') {
                        $res = call_user_func_array($route['function'], $matches);
                    } else if ($route['type'] === 'controller'){
                        [$class, $func] = self::extract_controller($route['function']);
                        $res = (new $class())->$func($matches);
                    } else $res = $route['function'];
                    View::staticRender($res);
                    $route_match_found = true;
                    break;
                }
            }
        }
        if (!$route_match_found) {
            $res = '';
            if ($path_match_found) {
                header('HTTP/1.0 405 Method Not Allowed');
                if (self::$methodNotAllowed) {
                    $res = call_user_func_array(self::$methodNotAllowed, [$path, $method]);
                }
            } else {
                header('HTTP/1.0 404 Not Found');
                if (self::$pathNotFound) {
                    $res = call_user_func_array(self::$pathNotFound, [$path]);
                }
            }
            View::staticRender($res);
        }
    }

    private static function is_controller($path)
    {
        return self::extract_controller($path) ? true : false;
    }

    private static function extract_controller($path)
    {
        $arr = array_values(array_filter(explode('@', $path), 'strlen'));
        if (count($arr) === 2) {
            $ns = '\\App\\Controllers\\';
            $class = $ns . $arr[0];
            $func = $arr[1];
            if (class_exists($class) && method_exists($class, $func))
                return [$class, $func];

        }
        return false;
    }
}