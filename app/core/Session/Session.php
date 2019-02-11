<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 11.02.2019
 * Time: 17:53
 */

namespace App\Core\Session;

class Session
{

    public static function start(): void
    {
        session_start();
    }

    /**
     * @param null $newId
     * @return string
     */
    public static function id($newId = null): string
    {
        if ($newId === null) {
            return session_id();
        }
        return session_id($newId);
    }

    /**
     * @param bool $deleteOldSession
     */
    public static function regenerate($deleteOldSession = false): void
    {
        session_regenerate_id($deleteOldSession);
    }

    /**
     * @param $key
     * @return bool
     */
    public static function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    public static function get($key, $defaultValue = null)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return $defaultValue;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     */
    public static function delete($key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * @param $key
     * @param null $defaultValue
     * @return null
     */
    public static function take($key, $defaultValue = null)
    {
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return $defaultValue;
    }
}