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
    router\URL,
    dir\dir,
    config\config,
    autoloader\autoloader
};

/** Подключение */
autoloader::getInstance()->register();
autoloader::getInstance()->addNamespace('core', __DIR__ . DIRECTORY_SEPARATOR . 'core');
autoloader::getInstance()->addNamespace('application', __DIR__ . DIRECTORY_SEPARATOR . 'application');

/** Задание путей */
dir::setDR(__DIR__);
dir::setDirConfig('configuration');
dir::setDirFileCache('filecache');

/** Маршрутизация */
$scheme = config::getConfig('structure');
$URL    =   explode('/', rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$URL[0] =   '/';

$URLTwo = $URL;
$router  =   (new router())->addStructure($scheme);

$result = false;
if (isset($URL[1])) {
    unset($URL[0]);
    $URL    =   array_values($URL);
    URL::setURI($URL);
    $result = $router->execute();
}

if ($result === false) {
    URL::setURI($URLTwo);
    $result = $router->execute();
}

/** Вывод */
if ($result === false) {
    echo 'Нет приложения';
} else {
    /** @var int Время Конца */
    $timeEnd = microtime(true);
    /** @var int Время Разница */
    $timeDiff = $timeEnd - $timeStart;
    echo strtr($result, Array(
        '{time_DIFF}' => $timeDiff,
        '{core_VERSION}' => core::VERSION,
    ));
}
