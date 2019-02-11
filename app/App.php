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

    public $name = 'SimpleMVC';
    public $version = '0.1.7';
    public $db;
    private static $instance;

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
    public static function getInstance() : App
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run(): void
    {
        $this->includeHelpers();
        $this->includeControllers();
        $this->collectRoutes();
        Route::run();
    }

    private function collectRoutes(): void
    {
        include_once ROUTES_PATH . 'route.php';
    }

    private function includeHelpers(): void
    {
        $helpers = glob(HELPERS_PATH . '*.php');
        foreach ($helpers as $helper) {
            include_once $helper;
        }
    }

    private function includeControllers()
    {

    }
}