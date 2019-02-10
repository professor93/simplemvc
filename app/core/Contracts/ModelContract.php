<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 10.02.2019
 * Time: 0:11
 */

namespace App\Core\Contracts;


interface ModelContract
{
    public function __construct(array $items = []);

    public static function all();

    public static function find(int $id);

    public static function create(array $data);

    public function save();

    public function delete();

    public function toArray();

    public function toJson();
}