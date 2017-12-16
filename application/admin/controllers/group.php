<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 0:57
 */

namespace application\admin\controllers;

use application\admin\model as model;
use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};


class group extends applicationWeb\AControllers implements applicationWeb\IControllers
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
                'field'             =>  'name',
                'label'             =>  'Название',
                'required'          =>  true,
                'grid'              =>  '1-2'
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'status',
                'label'             =>  'Статус',
                'grid'              =>  '1-2',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
        );
        self::$content  =    model\CFormDefault::generation($this, $field);

    }

}