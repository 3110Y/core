<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 19:15
 */

namespace application\admin\controllers\system\rules;

use application\admin\model;
use core\{
    application\controller\AController,
    router\route
};


/**
 * Class rulesObjects
 * @package application\controllers
 */
class rulesObjects extends AController
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   false;

    /**
     * Инициализация
     * @param route $route
     */
    public function __construct(route $route)
    {
        $field     =   Array(
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'name',
                'label'             =>  'Название',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
        );
        self::$content  =    model\CFormDefault::generation($this, 'core_rules_objects', 'Правила', $field);

    }

}