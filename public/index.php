<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 22:32
 */

use App\App;
use App\Bootloader;

//require_once '../app/Bootloader.php';
require_once '../vendor/autoload.php';

define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APP_PATH', ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', ROOT_PATH . 'configs' . DIRECTORY_SEPARATOR);
define('MODELS_PATH', ROOT_PATH . 'models' . DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers' . DIRECTORY_SEPARATOR);
define('ROUTES_PATH', ROOT_PATH . 'routes' . DIRECTORY_SEPARATOR);
define('HELPERS_PATH', ROOT_PATH . 'helpers' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', ROOT_PATH . 'views' . DIRECTORY_SEPARATOR);


Bootloader::autoload();

$app = App::getInstance();
$app->run();