<?php

/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:09
 */

namespace App;


use App\Core\Contracts\SingletonContract;
use App\Core\Database\DB;
use App\Core\Route;
use Config;

class App implements SingletonContract
{

    public $name = 'AppName';
    public $version = '0.0.1';
    public $db = null;
    private static $instance = null;

    public function __construct()
    {
        $this->name = Config::get('app.name', $this->name);
        $this->version = Config::get('app.version', $this->version);
        $this->db = new DB([
            'database_type' => Config::get('db.type'),
            'database_name' => Config::get('db.database'),
            'server' => Config::get('db.host'),
            'username' => Config::get('db.user'),
            'password' => Config::get('db.password')
        ]);
    }

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run()
    {
        include_once(APP_PATH . 'routes/route.php');
        Route::run();
    }
}