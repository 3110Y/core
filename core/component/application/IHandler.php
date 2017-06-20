<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 12:23
 */

namespace core\component\application;

/**
 * Interface IHandler
 * @package core\component\application
 */
interface IHandler
{
    /**
     * Отдает экземпляр роутера приложения
     * @param array $url URL
     * @param array $application настройки приложения
     * @return mixed|string результат работы приложения
     */
    public static function factory(array $url, array $application);
}