<?php
/**
 * Created by PhpStorm.
 * User: professor
 * Date: 09.02.2019
 * Time: 22:32
 */

use App\App;

require_once '../vendor/autoload.php';

define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('CONFIG_PATH', APP_PATH . 'configs' . DIRECTORY_SEPARATOR);

$app = App::getInstance();
$app->run();

