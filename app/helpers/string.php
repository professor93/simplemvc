<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:59
 */


if (!function_exists('pluralize')) {
    /**
     * String Pluralizer
     * @param $string
     * @return null|string|string[]
     */
    function pluralize($string)
    {
        return App\Core\Helpers\Inflect::pluralize($string);
    }
}

if (!function_exists('singularize')) {
    /**
     * String Singularizer
     * @param $string
     * @return null|string|string[]
     */
    function singularize($string)
    {
        return App\Core\Helpers\Inflect::singularize($string);
    }
}

if (!function_exists('camel_case')) {
    /**
     * Convert a value to camel case
     * @param  string $value
     * @return string
     */
    function camel_case($value)
    {
        static $camelCache = [];
        if (isset($camelCache[$value])) {
            return $camelCache[$value];
        }
        return $camelCache[$value] = lcfirst(studly($value));
    }
}

if (!function_exists('studly')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @return string
     */
    function studly($value)
    {
        static $studlyCache = [];
        $key = $value;
        if (isset($studlyCache[$key])) {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

if (!function_exists('studly_case')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string $value
     * @return string
     */
    function studly_case($value)
    {
        static $studlyCache = [];
        $key = $value;
        if (isset($studlyCache[$key])) {
            return $studlyCache[$key];
        }
        $value = ucwords(str_replace(array('-', '_'), ' ', $value));
        return $studlyCache[$key] = str_replace(' ', '', $value);
    }
}

if (!function_exists('title_case')) {
    /**
     * Convert a value to title case.
     *
     * @param  string $value
     * @return string
     */
    function title_case($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('snake_case')) {
    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        static $snakeCache = [];
        $key = $value . $delimiter;
        if (isset($snakeCache[$key])) {
            return $snakeCache[$key];
        }
        if (!ctype_lower($value)) {
            $value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1' . $delimiter, $value));
        }
        return $snakeCache[$key] = $value;
    }
}

if ( ! function_exists('e'))
{
    /**
     * Escape HTML entities in a string.
     *
     * @param  string  $value
     * @return string
     */
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}