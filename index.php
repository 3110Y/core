<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 11:12
 */

error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');

$method = $_SERVER['REQUEST_METHOD'];
$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

/** @var int Время Старта */
$timeStart  = microtime(true);

include_once  'core'. DIRECTORY_SEPARATOR .  'autoloader' . DIRECTORY_SEPARATOR .  'autoloader.php';

use core\{
    core,
    router\router,
    dir\dir,
    config\config,
    autoloader\autoloader
};

/** Подключение */
autoloader::getInstance()->register();
autoloader::getInstance()->addNamespace('core', __DIR__ . DIRECTORY_SEPARATOR . 'core');

/** Задание путей */
dir::setDR(__DIR__);
dir::setDirConfig('configuration');
dir::setDirFileCache('filecache');

/** Маршрутизация */
$scheme = config::getConfig('structure');
$URI    =   explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$URI[0] =   '/';
$URI    =   array_values($URI);
router::setURI($URI);
router::setMethod($_SERVER['REQUEST_METHOD']);
router::setPort($_SERVER['SERVER_PORT']);
router::setSite($_SERVER['HTTP_HOST']);
router::addStructure($scheme);
$result = router::execute();
var_dump($result);
/** Вывод */
/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
    '{core_VERSION}' => core::VERSION,
));
