<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 13:54
 */

namespace application\admin\controllers\system\common;

use core\{
    application\AControllers,
    application\IControllerBasic,
    router\URL,
    simpleView\simpleView,
    registry\registry,
    resources\resources
};
use \application\admin\model\menu;


/**
 * Class basic
 * @package application\admin\controllers\system\common
 */
class basic extends AControllers implements IControllerBasic
{

    /**
     * Преинициализация
     */
    public static function pre() : void
    {


    	//TODO: проверить title
        $path                           =   self::$path;
        $theme                          =   self::$theme;
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   URL::getURLPointerNow();
        $data                           =   Array(
            'MENU'  => (new menu('admin_page'))->getMenu(URL::getPointer(), URL::getFullURLPointerNow()),
        );
        $template                       =   self::getTemplate('block/menu/menu.tpl');
        self::$content['MENU']          =   simpleView::replace($template,  $data);
    }

    /**
     * Преинициализация
     */
    public static function preAjax() : void
    {

    }

	/**
	 * @param string           $parentURL   родительский URL
	 * @param int              $parentID    родительский уровень
	 *
	 * @return array    меню
	 */
    private static function generationMenu($parentURL = '/', $parentID = 0): array
    {
        /** @var \core\PDO\PDO $db */
        $db = registry::get('db');
        self::getURL(1);
        $where  =   Array(
            'parent_id' => $parentID,
            '`order_in_menu` != 0',
            '`status` = 1',
            '`error` = 0',
        );
        /** @var \core\PDO\PDO $db */
        $query  =   $db->select('admin_page', '*', $where, 'order_in_menu');
        $rows   =   Array();
        $parentClass =  '';
	    $parentURL  =   $parentURL !== '/'   ?   $parentURL . '/'  :   $parentURL;
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                $class  =   '';
                $URL    =   $row['url'] == '/'    ?   $parentURL :   $parentURL . $row['url'];
                if ($row['url'] == self::$page['url'] && $row['parent_id'] == self::$page['parent_id']) {
                    $class          .=  'active ';
                    $parentClass    =   'open ';
                }
                $sub    =   '';
                $subLink =   '';
                $children   =   self::generationMenu($URL, $row['id']);
                if (!empty($children)) {
                    $sub        =   $children['sub'];
                    $class      .=  $children['class'] . ' ';
                    $subLink    =  simpleView::replace(self::getTemplate('block/menu/subLink.tpl'));
                }


                $rows[] =   Array(
                    'URL'           =>  $URL,
                    'NAME'          =>  $row['name'],
                    'ICON'          =>  $row['icon'],
                    'CLASS'         =>  $class,
                    'SUB'           =>  $sub,
                    'SUB_LINK'      =>  $subLink,
                );
            }
            $parentClass    .=  'parent ';
        }
        if (!empty($rows)) {
            return Array(
                    'sub'   =>  simpleView::loop('FOR', $rows,'', self::getTemplate('block/menu/subMenu.tpl')),
                    'class' =>  $parentClass
                );
        }
        return  Array();
    }

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
    public static function postAjax() : void
    {

    }

}
