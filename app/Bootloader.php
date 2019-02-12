<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 11.02.2019
 * Time: 14:11
 */

namespace App;

class Bootloader
{
    public static function autoload()
    {
        spl_autoload_register(function ($class) {
            $extClass = explode('\\', $class);
            $iMax = count($extClass) - 1;
            $filename = ROOT_PATH;
            for ($i = 0; $i < $iMax; $i++) {
                $filename .= strtolower($extClass[$i]) . DIRECTORY_SEPARATOR;
            }
            $filename .= end($extClass) . '.php';
            if (file_exists($filename) && is_readable($filename)) {
                include_once $filename;
            }
        });
    }

}