<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 13:54
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    database                            as database,
    application             as application,
    simpleView\simpleView,
    registry\registry,
    resources\resources
};


/**
 * Class basic
 * @package application\admin\controllers\system\common
 */
class basic extends application\AControllers implements application\IControllerBasic
{

    /**
     * Преинициализация
     */
    public function pre()
    {
    	//TODO: проверить title
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;
        self::$content['TITLE']         =   self::$page['meta_title'];
        self::$content['KEYWORDS']      =   self::$page['meta_keywords'];
        self::$content['DESCRIPTION']   =   self::$page['meta_description'];
        $data                           =   Array(
            'MENU'  => self::generationMenu(self::$application['url'])['sub'],
        );
        $template                       =   self::getTemplate('block/menu/menu.tpl');
        self::$content['MENU']          =   simpleView::replace($template,  $data);
    }

    /**
     * Преинициализация
     */
    public function preAjax()
    {

    }

	/**
	 * @param database\ADriver $db          Драйвер ДБ
	 * @param string           $parentURL   родительский URL
	 * @param int              $parentID    родительский уровень
	 *
	 * @return array    меню
	 */
    private static function generationMenu($parentURL = '/', $parentID = 0)
    {
        /** @var \core\component\PDO\PDO $db */
        $db = registry::get('db');
        self::getURL(1);
        $where  =   Array(
            'parent_id' => $parentID,
            '`order_in_menu` != 0',
            '`status` = 1',
            '`error` = 0',
        );
        /** @var \core\component\PDO\PDO $db */
        $query  =   $db->select('admin_page', '*', $where, 'order_in_menu');
        $rows   =   Array();
        $parentClass =  '';
	    $parentURL  =   $parentURL != '/'   ?   $parentURL . '/'  :   $parentURL;
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                /** @var \core\component\authentication\component $auth */
                $auth = registry::get('auth');
                $auth->get('authorization')->check();
                $auth->get('object')->register(
                    'application_' . self::$application['id'] . '_page_' . $row['id'],
                    'Приложение: ' . self::$application['name']. " Отображать пункт меню: {$row['name']}"
                );
                if (!$auth->get('rules')->check('application_' . self::$application['id'] . '_page_' . $row['id'])) {
                    continue;
                }
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
    public function post()
    {
        self::$content['JS_TOP']        =   resources::getJS();
        self::$content['CSS_TOP']       =   resources::getCSS();
        self::$content['JS_BOTTOM']     =   resources::getJS(false);
        self::$content['CSS_BOTTOM']    =   resources::getCSS(false);
    }

    /**
     * Постинициализация
     */
    public function postAjax()
    {

    }

}
