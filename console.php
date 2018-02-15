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
    router\URL,
    dir\dir,
    config\config,
    autoloader\autoloader
};


if (isset($_SERVER['SHELL'], $argv)) {

    /** Подключение */
    autoloader::getInstance()->register();
    autoloader::getInstance()->addNamespace('core', __DIR__);
    autoloader::getInstance()->addNamespace('application', __DIR__ . DIRECTORY_SEPARATOR . 'application');

    /** Задание путей */
    dir::setDR(__DIR__);
    dir::setDirConfig('configuration');
    dir::setDirFileCache('filecache');

    /** Маршрутизация */
    $scheme = config::getConfig('structure');
    $URL    =   $argv;

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
    echo $result !== false  ?   $result :   'Нет приложения';
}
exit(0);
