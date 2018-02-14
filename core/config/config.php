<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.02.18
 * Time: 16:59
 */

namespace core\config;


use core\dir\dir;

/**
 * Class config
 * @package core\config
 */
class config
{
    /**
     * @var array хранит конфигурации
     */
    private static $config = Array();

    /**
     * Отдает определенный конфиг
     * @param string $configName имя конфига
     *
     * @return array|mixed конфиг
     */
    public static function getConfig(string $configName)
    {
        if (isset(self::$config[$configName])) {
            return self::$config[$configName];
        }
        $globalDirConfig = dir::getDirConfig() . $configName . '.php';
        if (file_exists($globalDirConfig)) {
            $config = include $globalDirConfig;
            self::$config[$configName] = $config;
            return $config;
        }
        return Array();
    }
}