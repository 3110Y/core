<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:42
 */

namespace core\components\database;

use core\core;

/**
 * Class component
 * Базовый компонент Базы данных
 * @package core\components\database
 */
class component
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'database';

    /**
     * @var array драйвера
     */
    private static $drivers =  array();

    /**
     * @param string $driver Драйвер
     * @param array $config конфиг
     * @return mixed|object драйвер
     */
    public static function getDriver($driver)
    {
        //TODO: проверка на наличие
        return core::getComponents($driver,true);
    }

}