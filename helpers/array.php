<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:59
 */


use App\Core\Helpers\ArrayHelper;

if (!function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        return ArrayHelper::set($array, $key, $value);
    }
}

if (!function_exists('array_dot')) {
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array $array
     * @param  string $prepend
     * @return array
     */
    function array_dot($array, $prepend = '')
    {
        return ArrayHelper::dot($array, $prepend);
    }
}

if (!function_exists('array_undot')) {
    /**
     * Process dot array will return undot array.
     *
     * @param array $dotNotationArray
     *
     * @return array
     */
    function array_undot(array $dotNotationArray)
    {
        return ArrayHelper::undot($dotNotationArray);
    }
}

if (!function_exists('array_where')) {
    /**
     * Filter the array using the given Closure.
     *
     * @param  array $array
     * @param  \Closure $callback
     * @return array
     */
    function array_where($array, Closure $callback)
    {
        return ArrayHelper::where($array, $callback);
    }
}