<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 15.5.2017
 * Time: 11:46
 */

namespace application\admin\controllers;

use application\admin\model as model;
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
	    $field     =   Array(
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'login',
                'label'             =>  'Логин',
                'required'          =>  true,
                'grid'              =>  '1-2'
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
        self::$content  =    model\CFormDefault::generation($this, $field);

    }

}
