<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 30.11.2017
 * Time: 16:29
 */

namespace core\component\CForm;
use core\core;


/**
 * Class ACForm
 *
 * @package core\component\CForm
 */
abstract class ACForm
{
    /** @var \core\component\database\driver\PDO\component */
    protected static $db;

    /** @var \application\admin\controllers\page */
    protected static $controller;

    /** @var string */
    protected static $table;

    /** @var string  */
    protected static $caption = 'Список';

    /** @var string  */
    protected static $mode = 'listing';

    /** @var int  */
    protected static $id = 0;

    /** @var  array */
    protected static $subURL = Array();

    /**
     * @var int
     */
    protected static $subURLNow = 0;

    /**
     * @var bool
     */
    protected static $isWork = true;


    /**
     * @var array просмотрщик
     */
    protected static  $viewerConfig        =   Array();


    /**
     * отдает шаблон
     * @param string $template шаблон
     * @param string $dir
     *
     * @return string шаблон
     */
    protected static function getTemplate(string $template, string $dir = __DIR__): string
    {
        $dir    =   strtr($dir, Array(
            '\\' =>  '/'
        ));
        $dr    =   strtr(core::getDR(), Array(
            '\\' =>  '/'
        ));
        return '/' . str_replace($dr,'', $dir) . '/' . $template;
    }

    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected static function redirect($url, $isExternal = false)
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol = 'http';
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
            }
            $url        =   $protocol . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }

}