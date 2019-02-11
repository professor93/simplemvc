<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:59
 */


use App\Core\Helpers\StringHelper;

if (!function_exists('pluralize')) {
    /**
     * String Pluralizer
     * @param $string
     * @return null|string|string[]
     */
    function pluralize($string)
    {
        return App\Core\Helpers\StringHelper::pluralize($string);
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
        return App\Core\Helpers\StringHelper::singularize($string);
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
        return StringHelper::toCamelCase($value);
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
        return StringHelper::toStudlyCase($value);
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
        return StringHelper::toTitleCase($value);
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
        return StringHelper::toSnakeCase($value, $delimiter);
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