<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 20.03.18
 * Time: 14:19
 */

namespace core\application\controller;

use core\{
    application\application, URI\URL, resources\resources
};

abstract class ABasic extends AController
{

    /**
     * Преинициализация
     */
    public static function pre() : void
    {
        $path                           =   application::getPath();
        $theme                          =   application::getTheme();
        application::setDataKey('theme', "/application/{$path}/theme/{$theme}/");
        application::setDataKey('url', URL::getURLPointerNow());
    }

    /**
     * Преинициализация
     */
    abstract public static function preAjax();

    /**
     * Постинициализация
     */
    public static function post() : void
    {
        application::setDataKey('js_top', resources::getJS());
        application::setDataKey('css_top', resources::getCSS());
        application::setDataKey('js_bottom', resources::getJS(false));
        application::setDataKey('css_bottom', resources::getCSS(false));
    }

    /**
     * Постинициализация
     */
    abstract public static function postAjax();
}