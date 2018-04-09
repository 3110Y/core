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

/** @var int Время Старта */
$timeStart  = microtime(true);

include_once 'core' . DIRECTORY_SEPARATOR . 'autoload.php';

use core\{
    core,
    router\router,
    URI\URL,
    dir\dir,
    config\config,
    autoloader\autoloader
};
autoloader::getInstance()->addNamespace('application', __DIR__ . DIRECTORY_SEPARATOR . 'application');

/** Задание путей */
dir::setDR(__DIR__);
dir::setDirConfig('configuration');
dir::setDirFileCache('filecache');

/** Маршрутизация */
$scheme =   config::getConfig('structure');
$URL    =   parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (false === $URL) {
    throw new RuntimeException('Не возможно разобрать URL');
}
$URL    =   explode('/', rtrim($URL, '/'));
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
if ($result === false || !isset($URL[1])) {
    URL::setURI($URLTwo);
    $result = $router->execute();
}
if ($result === false) {
    $result = 'Нет приложения';
}

/** Вывод */
/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница */
$timeDiff = $timeEnd - $timeStart;
echo strtr($result->render(), Array(
    '{time_DIFF}' => $timeDiff,
    '{core_VERSION}' => core::VERSION,
));
