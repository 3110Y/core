<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 13:54
 */

namespace application\admin\controllers;

use \core\component\{
    database                            as database,
    application\handler\Web             as applicationWeb,
    templateEngine\engine\simpleView    as simpleView
};


/**
 * Class basic
 * @package application\admin\controllers
 */
class basic extends applicationWeb\AControllers implements applicationWeb\IControllers, applicationWeb\IControllerBasic
{

    /**
     * Преинициализация
     */
    public function preInit()
    {
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$template                 =   self::getTemplate(self::$page['template']);
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;
        self::$content['TITLE']         =   self::$page['meta_title'];
        self::$content['KEYWORDS']      =   self::$page['meta_keywords'];
        self::$content['DESCRIPTION']   =   self::$page['meta_description'];
        /** @var \core\component\database\driver\PDO\component $db */
        $db                             =   self::get('db');
        $data                           =   Array(
            'MENU'  => self::generationMenu($db)['sub'],
        );
        $template                       =   self::getTemplate('block/menu/menu.tpl');
        self::$content['MENU']          =   simpleView\component::replace($template,  $data);
    }

    /**
    * @param database\ADriver $db Драйвер ДБ
    * @param int $parentID родительский уровень
    * @param string $parentURL родительский URL
    * @return array меню
    */
    private static function generationMenu(database\ADriver $db, $parentID = 0, $parentURL = '/')
    {
        self::getURL(1);
        $where  =   Array(
            'parent_id' => $parentID,
            'order_in_menu != 0',
            'status = 0',
            'error = 0',
        );
        /** @var \core\component\database\driver\PDO\component $db */
        $query  =   $db->select('admin_page', '*', $where, 'order_in_menu');
        $rows   =   Array();
        $parentClass =  '';
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                $class  =   '';
                $URL    =   $row['url'] == '/'    ?   $row['url'] :   $parentURL . $row['url'];
                if ($row['url'] == self::$page['url'] && $row['parent_id'] == self::$page['parent_id']) {
                    $class          .=  'active';
                    $parentClass    =   'open';
                }
                $sub    =   '';
                $subLink =   '';
                $children   =   self::generationMenu($db, ++$parentID, $row['url'] . '/');
                if (!empty($children)) {
                    $sub        =   $children['sub'];
                    $class      .=  $children['class'];
                    $subLink    =  simpleView\component::replace(self::getTemplate('block/menu/subLink.tpl'));
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
            $parentClass    .=  'parent';
        }
        if (!empty($rows)) {
            return Array(
                    'sub'   =>  simpleView\component::loop('FOR', $rows,'', self::getTemplate('block/menu/subMenu.tpl')),
                    'class' =>  $parentClass
                );
        }
        return  Array();
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
