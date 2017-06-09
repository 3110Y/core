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
	    $schema     =   Array(
		    Array(
			    'type'              =>  'input',
			    'field'             =>  'name',
			    'caption'           =>  'Имя',
			    'placeholder'       =>  'Имя',
			    'label'             =>  'Имя',
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view'
			    )
		    ),
		    Array(
			    'type'              =>  'input',
			    'field'             =>  'surname',
			    'caption'           =>  'Фамилия',
			    'placeholder'       =>  'Фамилия',
			    'label'             =>  'Фамилия',
			    'required'          =>  true,
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view'
			    )
		    ),
		    Array(
			    'type'              =>  'input',
			    'field'             =>  'patronymic',
			    'caption'           =>  'Отчество',
			    'placeholder'       =>  'Отчество',
			    'label'             =>  'Отчество',
			    'required'          =>  true,
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view'
			    )
		    ),
	    );
	    $config     =   Array(
		    'controller'    =>  $this,
		    'db'            =>  self::get('db'),
		    'table'         =>  'core_user',
		    'caption'       =>  'Пользователи',
		    'defaultMode'   =>  'listing',
		    'viewer'        => Array(
			    'listing'      =>  Array(
			    	'viewer'            =>  'listing',
				    'template'          =>  'block/form/list.tpl',
				    'templateNoData'    =>  'block/form/listNo.tpl',
				    'css'               =>  Array(
					    Array(
						    'file'  =>  'block/form/css/style.css'
					    ),
					    Array(
						    'file'  =>  'block/form/css/list.css'
					    ),
				    ),
				    'field'             =>  $schema,
				    'action'            =>  Array(
				    	'row'       =>  Array(
						    'edit'      =>  Array(
						    	'method'    =>  'one'
						    ),
						    'dell'      =>   Array(
							    'method'    =>  'one'
						    ),
					    ),
				    	'rows'      =>  Array(
						    'add'       =>  Array(
							    'method'    =>  'many'
						    ),
						    'dell'      =>  Array(
							    'method'    =>  'many'
						    ),
					    ),
				    ),
			    ),
			    'edit'      =>  Array(
				    'viewer'            =>  'form',
				    'template'          =>  'block/form/form.tpl',
				    'templateNoData'    =>  'block/form/formNo.tpl',
				    'css'               =>  Array(
					    Array(
						    'file'  =>  'block/form/css/style.css'
					    ),
					    Array(
						    'file'  =>  'block/form/css/form.css'
					    ),
				    ),
				    'field'             =>  $schema,
				    'action'            =>  Array(
				    	'bottomItem'    =>  Array(
						    'back'      =>   Array(
							    'method'    =>  'one'
						    ),
						    'dell'      =>   Array(
							    'method'    =>  'one'
						    ),
						    'save'      =>  Array(
							    'method'    =>  'one',
						    ),
					    ),
				    ),
			    ),
			    'dell'      =>  Array(
				    'viewer'            =>  'dell',
				    'field'             =>  $schema,
			    ),
			    'save'      =>  Array(
				    'viewer'            =>  'save',
				    'field'             =>  $schema,
			    ),
			    'add'      =>  Array(
				    'viewer'            =>  'add',
				    'field'             =>  $schema,
			    ),
            ),

	    );


	    $CForm  =   new CForm\component(self::$content, 'CONTENT');
	    $CForm->setConfig($config);
	    $CForm->run();
	    self::$content  =    $CForm->getIncomingArray();

		/**
        $config     =   Array(
			'controller'    =>  $this,
	        'db'            =>  self::get('db'),
	        'table'         =>  'core_user',
	        'caption'       =>  'Пользователи'
        );
        $schema     =   Array(
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'name',
		        'caption'           =>  'Имя',
		        'placeholder'       =>  'Имя',
		        'label'             =>  'Имя',
		        'listing'           =>  Array(
		        	'align' =>  'left'
		        )
	        ),
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'surname',
		        'caption'           =>  'Фамилия',
		        'placeholder'       =>  'Фамилия',
		        'label'             =>  'Фамилия',
		        'required'          =>  true,
		        'listing'           =>  Array(
			        'align' =>  'left'
		        )
	        ),
	        Array(
		        'type'              =>  'input',
		        'field'             =>  'patronymic',
		        'caption'           =>  'Отчество',
		        'placeholder'       =>  'Отчество',
		        'label'             =>  'Отчество',
		        'required'          =>  true,
		        'listing'           =>  Array(
			        'align' =>  'left'
		        )
	        ),
        );
	    $template   =   Array(
			'listing'      =>  Array(
				'template'  =>  'block/form/list.tpl',
				'js'        =>  Array(),
				'css'       =>  Array(
					Array(
						'file'  =>  'block/form/css/style.css'
					),
                    Array(
						'file'  =>  'block/form/css/list.css'
					),
				),
			),
		    'listingNo'      =>  Array(
				'template'  =>  'block/form/listNo.tpl',
				'js'        =>  Array(),
				'css'       =>  Array(
                    Array(
                        'file'  =>  'block/form/css/style.css'
                    ),
					Array(
						'file'  =>  'block/form/css/list.css'
					),
				),
			),
		    'form'      =>  Array(
				'template'  =>  'block/form/form.tpl',
				'js'        =>  Array(),
				'css'       =>  Array(
                    Array(
                        'file'  =>  'block/form/css/style.css'
                    ),
					Array(
						'file'  =>  'block/form/css/form.css'
					),
				),
			),
	    );
        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->setSchema($schema);
        $CForm->setTemplate($template);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();
		 **/
    }

}
