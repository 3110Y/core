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
    CForm
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
        /** @var \core\component\database\driver\PDO\component $db */
        $db         =   self::get('db');
        $where      =   Array(
            'status'    => 1
        );
        $row        =   $db->selectRows('core_group','`id`, `name`', $where);
        $list       =   ($row !== false)    ?   $row    :   Array();
        $listStatus =   Array(
            Array(
                'id'    =>  1,
                'name'  => 'Активно'
            ),
            Array(
                'id'    =>  2,
                'name'  => 'Неактивно'
            ),
            Array(
                'id'        =>  3,
                'name'      => 'Черновик',
                'disabled'  =>  true
            ),
        );
	    $schema     =   Array(
		    Array(
			    'type'              =>  'input',
			    'field'             =>  'login',
			    'caption'           =>  'Логин',
			    'placeholder'       =>  'Логин',
			    'label'             =>  'Логин',
                'required'          =>  true,
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view'
			    )
		    ),
            Array(
			    'type'              =>  'password',
			    'field'             =>  'password',
			    'caption'           =>  'Пароль',
			    'placeholder'       =>  'Введите новый пароль для изменения',
			    'label'             =>  'Пароль',
                'algorithm'         =>  'sha512',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view',
                    'view'  =>  false
			    )
		    ),
            Array(
			    'type'              =>  'input',
			    'field'             =>  'name',
			    'caption'           =>  'Имя',
			    'placeholder'       =>  'Имя',
			    'label'             =>  'Имя',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
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
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
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
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
			    'listing'           =>  Array(
				    'align' =>  'left',
				    'mode'  =>  'view'
			    )
		    ),
            Array(
			    'type'              =>  'select',
			    'field'             =>  'group_id',
			    'caption'           =>  'Группы',
			    'placeholder'       =>  'Группы',
			    'label'             =>  'Группы',
			    'list'              =>  $list,
                'multiple'          =>  'multiple',
                'viewNo'            =>  'Нет группы',
                'edit'              =>  Array(
                    'mode'  =>  'edit'
                ),
			    'listing'           =>  Array(
				    'align' =>  'center',
				    'mode'  =>  'view'
			    )
		    ),
            Array(
                'type'              =>  'select',
                'field'             =>  'status',
                'caption'           =>  'Статус',
                'placeholder'       =>  'Статус',
                'label'             =>  'Статус',
                'list'              =>  $listStatus,
                'NoZero'            =>  true,
                'edit'              =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'right',
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
				    'viewer'            =>  'edit',
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
				    	'item'    =>  Array(
						    'back'      =>   Array(
							    'method'    =>  'one',
                                'redirect'  =>  '/listing/{PAGE}',
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
                    'redirect'          =>  '/edit/{DATA_ID}',
                    'status'            =>  3
			    ),
            ),

	    );


	    $CForm  =   new CForm\component(self::$content, 'CONTENT');
	    $CForm->setConfig($config);
	    $CForm->run();
	    self::$content  =    $CForm->getIncomingArray();

    }

}
