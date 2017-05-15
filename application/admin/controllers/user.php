<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 15.5.2017
 * Time: 11:46
 */

namespace application\admin\controllers;

use \core\component\{
    application\handler\Web as applicationWeb,
    CForm                   as CForm
};


/**
* Class user
 * @package application\admin\controllers
 */
class user extends applicationWeb\AControllers implements applicationWeb\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
	public static $countSubURL  =   false;

    /**
     * Инициализация
     */
    public function init()
    {
        $config     =   Array(
        	'url'   =>  self::$page['url'],
			'sub'   =>  self::$subURL,
	        'db'    =>  self::get('db'),
        );
        $schema     =   Array(
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'title',
		        'collNameBig'       =>  true,
		        'collNameLittle'    =>  false,
		        'placeholder'       =>  'Заголовок страницы',
		        'label'             =>  'Заголовок'
	        ),
        );
	    $template   =   Array(
			'list'  =>  self::getTemplate('block/form/list.tpl'),
		    'form'  =>  self::getTemplate('block/form/form.tpl'),
	    );
        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->setSchema($schema);
        $CForm->setTemplate($template);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();
    }

}
