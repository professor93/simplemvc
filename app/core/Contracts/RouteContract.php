<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 6:00
 */

namespace App\Core\Contracts;

interface RouteContract
{
    public static function get($expression, $function, $name = '');

    public static function post($expression, $function, $name = '');

    public static function add($expression, $function, $method = 'get', $name = '');
}