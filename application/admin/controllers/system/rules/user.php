<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 15.5.2017
 * Time: 11:46
 */

namespace application\admin\controllers\system\rules;

use application\admin\model as model;
use \core\component\{
    application as application,
    CForm
};


/**
* Class user
 * @package application\admin\controllers
 */
class user extends application\AControllers implements application\IControllers
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
                'grid'              =>  '1-3'
            ),
            Array(
			    'type'              =>  'UKPassword',
			    'field'             =>  'password',
			    'label'             =>  'Пароль',
                'algorithm'         =>  'sha512',
                'grid'              =>  '1-3',
                'edit'           =>  Array(
                    'mode'          =>  'edit',
                    'label'         =>  'Введите новый пароль для изменения',
                ),
			    'listing'           =>  Array(
                    'view'  =>  false
			    )
		    ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'status',
                'label'             =>  'Статус',
                'grid'              =>  '1-3',
                'list'              =>  $listStatus,
            ),
            Array(
			    'type'              =>  'UKInput',
			    'field'             =>  'name',
			    'label'             =>  'Имя',
                'grid'              =>  '1-3'
		    ),
		    Array(
			    'type'              =>  'UKInput',
			    'field'             =>  'surname',
			    'label'             =>  'Фамилия',
                'grid'              =>  '1-3'
		    ),
		    Array(
			    'type'              =>  'UKInput',
			    'field'             =>  'patronymic',
			    'label'             =>  'Отчество',
                'grid'              =>  '1-3'
		    ),
            Array(
			    'type'              =>  'select2',
			    'table'        =>  Array(
                    'field'     =>  'id',
			        'link'      =>  'core_user_group',
                    'field_id'  =>  'user_id',
                    'table_id'  =>  'group_id',
                ),
			    'label'             =>  'Группы',
                'grid'              =>  '1-1',
			    'list'              =>  $list,
                'multiple'          =>  'multiple'
		    ),
	    );
        self::$content  =    model\CFormDefault::generation($this, 'core_user', 'Пользователи', $field);

    }

}
