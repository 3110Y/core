<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 17.01.2018
 * Time: 15:04
 */

namespace Core\Registry;


/**
 * Class registry
 * @package core\registry
 */
class Registry
{
    /**
     * @var mixed|null|object реестр
     */
    protected static $registry = array();


    /**
     * задает ключь и значение реестра
     * @param string $key ключ
     * @param mixed|string|object $class класс
     * @return boolean
     */
    public static function set($key, $class)
    {
        if (isset(self::$registry[$key])) {
            return false;
        }
        return self::$registry[$key] = $class;
    }

    /**
     * Отдает значение ключа реестра
     * @param string $key ключ
     * @return mixed|null|object рендер
     */
    public static function get($key)
    {
        //TODO: обработка ошибок
        if (isset(self::$registry[$key])) {
            return self::$registry[$key];
        }
        return false;
    }
}