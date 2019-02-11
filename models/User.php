<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:07
 */

namespace Models;

use App\Core\Model;


class User extends Model
{

    protected $tableName = 'users';
    protected $hidden_fields = ['remember_token', 'password'];

}