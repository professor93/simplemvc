<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:59
 */


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
        if (null === $key) return $array = $value;
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
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
        $results = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, array_dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
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
        $array = [];
        foreach ($dotNotationArray as $key => $value) {
            array_set($array, $key, $value);
        }
        return $array;
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
        $filtered = array();
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) $filtered[$key] = $value;
        }
        return $filtered;
    }
}