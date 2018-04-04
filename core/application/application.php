<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 04.04.18
 * Time: 20:11
 */

namespace core\application;


class application
{
    /**
     * @var string
     */
    private static $applicationURL;

    /**
     * @var int
     */
    private static $applicationPointer;

    /**
     * @var string
     */
    private static $theme;

    /**
     * @var string
     */
    private static $path;


    /**
     * отдает шаблон из темы
     * @param string $template шаблон
     * @return string шаблон
     */
    public static function getTemplate(string $template): string
    {
        $theme  =   self::$theme;
        $path   =   self::$path;
        $DS     =   DIRECTORY_SEPARATOR;
        return "{$DS}{$path}{$DS}theme{$DS}{$theme}{$DS}{$template}";
    }

    /**
     * @return string
     */
    public static function getTheme(): string
    {
        return self::$theme;
    }

    /**
     * @param string $theme
     */
    public static function setTheme(string $theme): void
    {
        self::$theme = $theme;
    }

    /**
     * @return int
     */
    public static function getApplicationPointer(): int
    {
        return self::$applicationPointer;
    }

    /**
     * @param int $applicationPointer
     */
    public static function setApplicationPointer(int $applicationPointer): void
    {
        self::$applicationPointer = $applicationPointer;
    }

    /**
     * @return string
     */
    public static function getApplicationURL(): string
    {
        return self::$applicationURL;
    }

    /**
     * @param string $applicationURL
     */
    public static function setApplicationURL(string $applicationURL): void
    {
        self::$applicationURL = $applicationURL;
    }

    /**
     * @return string
     */
    public static function getPath(): string
    {
        return self::$path;
    }

    /**
     * @param string $path
     */
    public static function setPath(string $path): void
    {
        self::$path = $path;
    }
}