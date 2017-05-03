<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 23:24
 */

namespace core\component\application\handler\Web;
use core\component\application as application;
use core\core;

/**
 * Class component
 * @package core\component\application\handler\Web
 */
class component extends application\AHandler implements application\IHandler
{
    /**
     * @const float Версия
     */
    const VERSION   =   1.0;

    /**
     * Отдает экземпляр роутера приложения
     * @param array $URL URL
     * @param array $application настройки приложения
     * @return mixed|string результат работы приложения
     */
    public static function factory(array $URL, array $application)
    {
        $namespace  =   'application\\' . $application['path'];
        core::getInstance()->addNamespace($namespace, $namespace);
        $application = $namespace . '\router';
        $router = new $application($URL, $application);
        $router->run();
        return $router->render();

    }
}