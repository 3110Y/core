<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 13:54
 */

namespace application\admin\controllers;


use core\component\application\handler\Web as handlerWeb;

/**
 * Class basic
 * @package application\admin\controllers
 */
class basic extends handlerWeb\AControllers implements handlerWeb\IControllers, handlerWeb\IControllerBasic
{

    /**
     * Преинициализация
     */
    public function preInit()
    {
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$content['THEME']         =   "/app/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;
        self::$content['TITLE']         =   self::$page['meta_title'];
        self::$content['KEYWORDS']      =   self::$page['meta_keywords'];
        self::$content['DESCRIPTION']   =   self::$page['meta_description'];
    }

    /**
     * Постинициализация
     */
    public function postInit()
    {
        self::$content['JS_TOP']    =   self::getJS();
        self::$content['CSS_TOP']   =   self::getCSS();
        self::$content['JS_BOTTOM']    =   self::getJS(false);
        self::$content['CSS_BOTTOM']   =   self::getCSS(false);
    }

    /**
     * Инициализация
     */
    public function init()
    {
        // TODO: Implement init() method.
    }
}