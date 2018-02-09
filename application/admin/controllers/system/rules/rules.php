<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 1:18
 */

namespace application\admin\controllers\system\rules;

use application\admin\model as model;
use \core\component\{
    application as application,
    registry\registry
};

/**
 * Class rules
 * @package application\admin\controllers
 */
class rules extends application\AControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   false;

    /**
     * Инициализация
     */
    public function __construct()
    {
        /** @var \core\component\PDO\PDO $db */
        $db         =   registry::get('db');
        $where      =   Array(
            'status'    => 1
        );
        $row        =   $db->selectRows('core_group','`id`, `name`', $where);
        $listGroup  =   ($row !== false)    ?   $row    :   Array();
        $array   =  Array(
            Array(
                'id' => 0,
                'name'  =>  'Нет группы или любая группа'
            ),
        );
        $listGroup = array_merge($array, $listGroup);
        $field      =   '`id`, CONCAT(`name`, " ",`surname`, " ", `patronymic`, " (", `login`, ")") as `name`';
        $row        =   $db->selectRows('core_user', $field, $where);
        $listUser   =   ($row !== false)    ?   $row    :   Array();
        $array   =  Array(
            Array(
                'id' => 0,
                'name'  =>  'Не авторизованный или любой '
            ),
        );
        $listUser = array_merge($array, $listUser);
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
                'name'  => 'Запрещено'
            ),
            Array(
                'id'    =>  1,
                'name'  => 'Разрешено'
            ),
        );
        $field     =   Array(
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'object_id',
                'label'             =>  'Обьект',
                'list'              =>  $listObject,
                'grid'              =>  '1-1',
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'group_id',
                'label'             =>  'Группа',
                'list'              =>  $listGroup,
                'grid'              =>  '1-3',
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'user_id',
                'label'             =>  'Пользователь',
                'list'              =>  $listUser,
                'grid'              =>  '1-3',
            ),

            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'action',
                'label'             =>  'Действие',
                'list'              =>  $listAction,
                'grid'              =>  '1-3',
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'status',
                'label'             =>  'Статус',
                'list'              =>  $listStatus,
                'grid'              =>  '1-2',
            ),
            Array(
                'type'              =>  'UKNumber',
                'field'             =>  'priority',
                'label'             =>  'Приоритет',
                'grid'              =>  '1-2',
            ),
        );
        self::$content  =    model\CFormDefault::generation($this, 'core_rules', 'Правила', $field);
    }

}