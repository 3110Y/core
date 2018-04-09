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
    application\application, application\controller\ABasic, resources\resources
};



/**
 * Class basic
 * @package application\admin\controllers\system\common
 */
class basic extends ABasic
{

    /**
     * Преинициализация
     */
    public static function pre() : void
    {
        parent::pre();
        application::setDataKey(
            'MENU',
            (new menu('admin_page'))->getMenu(
                application::getApplicationPointer(),
                application::getApplicationURL()
            )
        );
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
        resources::setCss(application::getTemplate('css/ui-kit-fix.css'));
        parent::post();
    }

    /**
     * Постинициализация
     */
    public static function postAjax() : void
    {

    }

}
