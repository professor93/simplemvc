<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:21
 */

namespace App\Core\Contracts;


interface ConfigContract
{
    /**
     * @param string $key
     * @param string|array|null $default
     * @return string|array
     */
    public static function get(string $key, $default=null);
}