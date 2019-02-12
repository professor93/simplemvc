<?php

/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 23:09
 */

namespace App;


use App\Core\Auth;
use App\Core\Contracts\SingletonContract;
use App\Core\Database\DB;
use App\Core\Route;
use App\Core\Config;


/**
 * @property DB db
 * @property Auth auth
 * @property bool isGuest
 * @property App instance
 */
class App implements SingletonContract
{

    public $name = 'SimpleMVC';
    public $version = '0.1.7';
    public $db;
    public $auth;
    public $isGuest;
    private static $instance;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->createDbConnection();
        $this->name = Config::get('app.name', $this->name);
        $this->version = Config::get('app.version', $this->version);
    }

    /**
     * @return App
     */
    public static function getInstance(): App
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run(): void
    {
        $this->auth = new Auth();
        $this->isGuest = $this->auth->user ? false : true;
        $this->includeHelpers();
        $this->collectRoutes();
        Route::run();
    }

    private function createDbConnection()
    {
        $this->db = new DB([
            'database_type' => Config::get('db.type'),
            'database_name' => Config::get('db.database'),
            'server' => Config::get('db.host'),
            'username' => Config::get('db.user'),
            'password' => Config::get('db.password')
        ]);
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
}