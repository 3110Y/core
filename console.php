<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.02.18
 * Time: 15:19
 */

error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');

include_once  'core'. DIRECTORY_SEPARATOR .  'autoloader' . DIRECTORY_SEPARATOR .  'autoloader.php';

use core\{
    router\router,
    dir\dir,
    config\config,
    autoloader\autoloader
};


if (isset($_SERVER['SHELL'], $argv)) {

    /** Подключение */
    autoloader::getInstance()->register();
    autoloader::getInstance()->addNamespace('core', __DIR__);

    /** Задание путей */
    dir::setDR(__DIR__);
    dir::setDirConfig('configuration');
    dir::setDirFileCache('filecache');

    /** Маршрутизация */
    $scheme = config::getConfig('structure');
    $URI    =   $argv;
    $URI[0] = '/';
    router::setURI($URI);
    router::addStructure($scheme);
    $result = router::execute();

    /** Вывод */
    echo $result;
}
exit(0);
