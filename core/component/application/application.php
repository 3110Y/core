<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 23:24
 */

namespace core\component\application;
use core\core;

/**
 * Class component
 * @package core\component\application
 */
class application
{

    /**
     * Отдает экземпляр роутера приложения
     * @param array $application настройки приложения
     * @param array $URL URL
     * @return mixed|string результат работы приложения
     */
    public static function create(array $application, array $URL)
    {
        $namespace              =   'application\\' . $application['path'];
        $path                   =   'application/' . $application['path'];
	    $application['url']     =   $application['url'] !== '/'   ?   '/' . $application['url']   :   $application['url'];
        core::getInstance()->addNamespace($namespace, $path);
        $router = $namespace . '\router';
        if (file_exists(core::getDR() . $path . '/router.php')) {
	        $isAjaxRequest  =   (
	            isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
	            $_SERVER['HTTP_REFERER'] !== '' &&
	            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            );
            $router = new $router($URL, $application, $isAjaxRequest);
            $router->run();
            return $router->render();
        } else {
            die('Нет приложения');
        }

    }
}