<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:05
 */


if (!function_exists('view')) {
    function view($template, array $args = [])
    {
        return \App\Core\View::make($template, $args);
    }
}

if (!function_exists('layout')) {
    function layout($template, array $args = [])
    {
        return \App\Core\View::make($template, $args)->getContent();
    }
}

if (!function_exists('app')) {
    function app()
    {
        return \App\App::getInstance();
    }
}

if (!function_exists('config')) {
    function config($key, $default=null)
    {
        return \App\Core\Config::get($key, $default);
    }
}

if (!function_exists('route')) {
    function route()
    {
        //TODO
        return '';
    }
}

