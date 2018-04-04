<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 20.03.18
 * Time: 14:19
 */

namespace core\application\controller;

use core\{
    URI\URL,
    resources\resources
};

class ABasic extends AController
{

    /**
     * Преинициализация
     */
    public static function pre() : void
    {
        $path                           =   self::$path;
        $theme                          =   self::$theme;
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   URL::getURLPointerNow();
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
        self::$content['JS_TOP']        =   resources::getJS();
        self::$content['CSS_TOP']       =   resources::getCSS();
        self::$content['JS_BOTTOM']     =   resources::getJS(false);
        self::$content['CSS_BOTTOM']    =   resources::getCSS(false);
    }

    /**
     * Постинициализация
     */
    abstract public static function postAjax();
}