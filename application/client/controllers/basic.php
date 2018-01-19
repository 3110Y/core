<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:06
 */

namespace application\client\controllers;

use \application\client\model as model;
use \core\component\{
    registry\registry                   as registry,
    database                            as database,
    application                         as application,
    templateEngine\engine\simpleView    as simpleView,
    resources\resources
};


/**
 * Class basic
 * @package application\client\controllers
 */
class basic extends application\AControllers implements application\IControllers, application\IControllerBasic
{

    /**
     * @var string шаблон
     */
    protected $template = 'basic';

    /**
     * Преинициализация
     */
    public function preInit()
    {
        //TODO: проверить title
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;
        if (self::$page['meta_title'] != '') {
            self::$content['TITLE'] = self::$page['meta_title'];
        } else {
            self::$content['TITLE'] = model\settings::getInstance()->getConfiguration('meta_title');
        }
        if (self::$page['meta_keywords'] != '') {
            self::$content['KEYWORDS'] = self::$page['meta_keywords'];
        } else {
            self::$content['KEYWORDS'] = model\settings::getInstance()->getConfiguration('meta_keywords');
        }
        if (self::$page['meta_description'] != '') {
            self::$content['DESCRIPTION'] = self::$page['meta_description'];
        } else {
            self::$content['DESCRIPTION'] = model\settings::getInstance()->getConfiguration('meta_description');
        }
        self::$content['DESCRIPTION']   =   self::$page['meta_description'];
        /** @var \core\component\database\driver\PDO\component $db */
        $db                             =   registry::get('db');

        $data                           =   Array(
            'MENU'  => self::generationMenu($db, self::$application['url'])
        );
        $template                       =   self::getTemplate('menu.tpl');
        self::$content['MENU']          =   simpleView\component::replace($template,  $data);

		$data                           =   Array(
			'MENU_MOBILE'  => self::generationMenu($db, self::$application['url']),
		);
		$template                       =   self::getTemplate('menu_mobile.tpl');
		self::$content['MENU_MOBILE']   =   simpleView\component::replace($template,  $data);
    }

    /**
     * @param database\ADriver $db          Драйвер ДБ
     * @param string           $parentURL   родительский URL
     * @param int              $parentID    родительский уровень
     *
     * @return array    меню
     */
    private static function generationMenu(database\ADriver $db, $parentURL = '/', $parentID = 0)
    {
        self::getURL(1);
        $where  =   Array(
            'parent_id' => $parentID,
            '`order_in_menu` != 0',
            '`status` = 1',
            '`error` = 0',
        );
        /** @var \core\component\database\driver\PDO\component $db */
        $query  =   $db->select('client_page', '*', $where, 'order_in_menu');
        $rows   =   Array();
        $parentURL  =   $parentURL != '/'   ?   $parentURL . '/'  :   $parentURL;
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                $class  =   '';
                $URL    =   $row['url'] == '/'    ?   $parentURL :   $parentURL . $row['url'];
                if ($row['url'] == self::$page['url'] && $row['parent_id'] == self::$page['parent_id']) {
                    $class          .=  'active ';
                }
                $rows[] =   Array(
                    'URL'           =>  $URL,
                    'NAME'          =>  $row['name'],
                    'CLASS'         =>  $class,
                );
            }
        }
        return  $rows;
    }

    /**
     * Постинициализация
     */
    public function postInit()
    {
        self::$content['JS_TOP']        =   resources::getJS();
        self::$content['CSS_TOP']       =   resources::getCSS();
        self::$content['JS_BOTTOM']     =   resources::getJS(false);
        self::$content['CSS_BOTTOM']    =   resources::getCSS(false);
    }

    /**
     * Инициализация
     */
    public function init()
    {
        foreach (self::$page as $key => $value) {
            self::$content['DATA_' . mb_strtoupper($key)]  =  $value;
        }

    }
}