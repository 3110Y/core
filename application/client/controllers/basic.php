<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 19:06
 */

namespace application\client\controllers;

use \core\component\{
    database                            as database,
    application\handler\Web             as applicationWeb,
    templateEngine\engine\simpleView    as simpleView
};


/**
 * Class basic
 * @package application\client\controllers
 */
class basic extends applicationWeb\AControllers implements applicationWeb\IControllers, applicationWeb\IControllerBasic
{

    /**
     * Преинициализация
     */
    public function preInit()
    {
        //TODO: проверить title
        $path                           =   self::$application['path'];
        $theme                          =   self::$application['theme'];
        self::$template                 =   self::getTemplate(self::$page['template']);
        self::$content['THEME']         =   "/application/{$path}/theme/{$theme}/";
        self::$content['URL']           =   self::$pageURL;
	    if (self::$page['meta_title'] != '') {
		    self::$content['TITLE'] = self::$page['meta_title'];
	    } else {
		    self::$content['TITLE'] = 'Детейлинг в Москве';
	    }
	    if (self::$page['meta_keywords'] != '') {
		    self::$content['KEYWORDS'] = self::$page['meta_keywords'];
	    } else {
		    self::$content['KEYWORDS'] = 'детейлинг, дейтелинг, детелинг, Москва, полировка, цены';
	    };
        self::$content['DESCRIPTION']   =   self::$page['meta_description'];
        /** @var \core\component\database\driver\PDO\component $db */
        $db                             =   self::get('db');

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

        self::setCss(self::getTemplate('css/font-awesome.min.css'));
        self::setCss(self::getTemplate('css/bootstrap.min.css'));
        self::setCss(self::getTemplate('css/jquery.fancybox.css'));
        self::setCss(self::getTemplate('css/jquery.fancybox-buttons.css'));
        self::setCss(self::getTemplate('css/indins.slider.css'));
        self::setCss(self::getTemplate('css/style.css'));
        self::setJs(self::getTemplate('js/jquery.2.1.4.min.js'));
        self::setJs(self::getTemplate('js/js.cookie.js'));
        self::setJs(self::getTemplate('js/bootstrap.min.js'));
        self::setJs(self::getTemplate('js/jquery.animateNumber.min.js'));
        self::setJs(self::getTemplate('js/modal.js'));
        self::setJs(self::getTemplate('js/js.js'));
        self::setJs(self::getTemplate('js/service.js'));
        self::setJs(self::getTemplate('js/art.js'));
        self::setJs(self::getTemplate('js/price.js'));
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
        self::$content['JS_TOP']        =   self::getJS();
        self::$content['CSS_TOP']       =   self::getCSS();
        self::$content['JS_BOTTOM']     =   self::getJS(false);
        self::$content['CSS_BOTTOM']    =   self::getCSS(false);
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