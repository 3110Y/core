<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.02.18
 * Time: 16:51
 */

namespace core\component\dir;


class dir
{
    /**
     * @var string CORE ROOT
     */
    private static $DR = '';

    /**
     * @var string директория конфигурации
     */
    private static $dirConfig = '';

    /**
     * @var string директория для файлов и кеша
     */
    private static $fileCache = '';


    /**
     * Устанавливает CORE ROOT;
     * @param string $DR DOCUMENT ROOT
     */
    public static function setDR(string $DR = __DIR__): void
    {
        self::$DR  =   str_replace('\\', '/', $DR);
    }

    /**
     * Отдает CORE ROOT
     *
     * @param bool $notSlash
     *
     * @return string CORE ROOT;
     */
    public static function getDR($notSlash = false): string
    {
        if (self::$DR !== '') {
            if ($notSlash) {
                return self::$DR;
            }
            return self::$DR . '/';

        }
        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            return $_SERVER['DOCUMENT_ROOT'];
        }
        return str_replace(array('\core', '\\'), array('', '/'), __DIR__);
    }


    /**
     * Устанавливает директорию для файлов и кеша
     * @param string $fileCache директория для файлов и кеша
     */
    public static function setDirFileCache(string $fileCache): void
    {
        self::$fileCache = self::getDR() . $fileCache . DIRECTORY_SEPARATOR;
    }

    /**
     * Отдает директорию для файлов и кеша
     *
     * @return string директория для файлов и кеша
     */
    public static function getDirFileCache(): string
    {
        return self::$fileCache;
    }


    /**
     * Устанавливает директорию конфигурации
     * @param string $dirConfig директория конфигурации
     */
    public static function setDirConfig(string $dirConfig): void
    {
        self::$dirConfig = self::getDR() . $dirConfig . DIRECTORY_SEPARATOR;
    }


    /**
     * Отдает директорию конфигурации
     *
     * @return string
     */
    public static function getDirConfig(): string
    {
        return self::$dirConfig;
    }

}