<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 13:54
 */

namespace application\admin\controllers\system\common;

use \application\admin\model\menu;
use core\{
    application\controller\AController,
    application\controller\IControllerBasic,
    URI\URL,
    simpleView\simpleView,
    registry\registry,
    resources\resources
};



/**
 * Class basic
 * @package application\admin\controllers\system\common
 */
class basic extends AController implements IControllerBasic
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
        self::$content['MENU']          = (new menu('admin_page'))->getMenu(
            self::$applicationPointer,
            self::$applicationURL
        );
        echo '<pre>';
        var_dump(self::$content['MENU']);
        echo '</pre>';
        die();
    }

    /**
     * Преинициализация
     */
    public static function preAjax() : void
    {

    }

    /**
     * Постинициализация
     */
    public static function post() : void
    {
        resources::setCss(self::getTemplate('css/ui-kit-fix.css'));
        self::$content['JS_TOP']        =   resources::getJS();
        self::$content['CSS_TOP']       =   resources::getCSS();
        self::$content['JS_BOTTOM']     =   resources::getJS(false);
        self::$content['CSS_BOTTOM']    =   resources::getCSS(false);
    }

    /**
     * Постинициализация
     */
    public static function postAjax() : void
    {

    }

}
