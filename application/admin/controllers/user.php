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
        	'url'       =>  self::$page['url'],
			'sub'       =>  self::$subURL,
	        'db'        =>  self::get('db'),
	        'table'     =>  'core_user',
        );
        $schema     =   Array(
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'name',
		        'placeholder'       =>  'Имя',
		        'label'             =>  'Имя',
		        'listing'           =>  Array(
		        	'align' =>  'center'
		        )
	        ),
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'surname',
		        'placeholder'       =>  'Фамилия',
		        'label'             =>  'Фамилия',
		        'listing'           =>  Array(
			        'align' =>  'center'
		        )
	        ),
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'surname',
		        'placeholder'       =>  'Отчество',
		        'label'             =>  'Отчество',
		        'listing'           =>  Array(
			        'align' =>  'center'
		        )
	        ),
        );
	    $template   =   Array(
			'listing'      =>  Array(
				'template'  =>  self::getTemplate('block/form/list.tpl'),
				'js'        =>  Array(),
				'css'       =>  Array(
					Array(
						'file'  =>  'block/form/css/list.css'
					),
				),
			),
		    'listingNo'      =>  Array(
				'template'  =>  self::getTemplate('block/form/listNo.tpl'),
				'js'        =>  Array(),
				'css'       =>  Array(
					Array(
						'file'  =>  'block/form/css/list.css'
					),
				),
			),
		    'form'      =>  Array(
				'template'  =>  self::getTemplate('block/form/form.tpl'),
				'js'        =>  Array(),
				'css'       =>  Array(),
			),
	    );
        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->setSchema($schema);
        $CForm->setTemplate($template);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();
	    self::addCss($CForm::getCss());
	    self::addJs($CForm::getJS());
    }

}
