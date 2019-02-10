<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 2:05
 */

namespace App\Core\Contracts;


interface SingletonContract
{
    /**
     * @return self
     */
    public static function getInstance();
}