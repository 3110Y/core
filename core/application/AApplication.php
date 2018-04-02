<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.5.2017
 * Time: 18:13
 */

namespace core\application;


/**
 * Class AApplication
 * @package core\application
 */
abstract class AApplication
{

    /**
     * @var string
     */
    protected static $applicationURL;

    /**
     * @var int
     */
    protected static $applicationPointer;

    /**
     * @var string
     */
    protected static $theme;

    /**
     * @var string
     */
    protected static $path;

    /**
     * @var \core\router\route
     */
    protected static $applicationRoute;

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







}