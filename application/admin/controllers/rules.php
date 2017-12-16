<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 1:18
 */

namespace application\admin\controllers;

use application\admin\model as model;
use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};

/**
 * Class rules
 * @package application\admin\controllers
 */
class rules extends applicationWeb\AControllers implements applicationWeb\IControllers
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
        $listGroup  =   ($row !== false)    ?   $row    :   Array();
        $field      =   '`id`, CONCAT(`name`, " ",`surname`, " ", `patronymic`, " (", `login`, ")") as `name`';
        $row        =   $db->selectRows('core_user', $field, $where);
        $listUser   =   ($row !== false)    ?   $row    :   Array();
        $row        =   $db->selectRows('core_rules_objects','`id`, `name`');
        $listObject =   ($row !== false)    ?   $row    :   Array();
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
        $listAction  =   Array(
            Array(
                'id'    =>  0,
                'name'  => 'Разрешено'
            ),
            Array(
                'id'    =>  1,
                'name'  => 'Показать авторизацию'
            ),
            Array(
                'id'    =>  2,
                'name'  => 'Показать 404'
            ),
            Array(
                'id'    =>  3,
                'name'  => 'Показать пустую страницу'
            ),
            Array(
                'id'    =>  4,
                'name'  => 'Показать пустоту'
            ),
        );
        $field     =   Array(
            Array(
                'type'              =>  'select',
                'field'             =>  'object_id',
                'caption'           =>  'Обьект',
                'placeholder'       =>  'Обьект',
                'label'             =>  'Обьект',
                'list'              =>  $listObject,
                'NoZero'            =>  true,
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
                'field'             =>  'action',
                'caption'           =>  'Действие',
                'placeholder'       =>  'Действие',
                'label'             =>  'Действие',
                'list'              =>  $listAction,
                'NoZero'            =>  true,
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
                'field'             =>  'user_id',
                'caption'           =>  'Пользователь',
                'placeholder'       =>  'Пользователь',
                'label'             =>  'Пользователь',
                'list'              =>  $listUser,
                'def'               =>  'Все',
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
                'field'             =>  'group_id',
                'caption'           =>  'Группа',
                'placeholder'       =>  'Группа',
                'label'             =>  'Группа',
                'list'              =>  $listGroup,
                'def'               =>  'Все',
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
            Array(
                'type'              =>  'number',
                'field'             =>  'priority',
                'caption'           =>  'Приоритет',
                'placeholder'       =>  'Приоритет',
                'label'             =>  'Приоритет',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'center',
                    'mode'  =>  'view'
                )
            ),
        );
        self::$content  =    model\CFormDefault::generation($this, 'core_rules', 'Правила', $field);
    }

}