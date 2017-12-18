<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 19:15
 */

namespace application\admin\controllers\system\rules;

use application\admin\model as model;
use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};


/**
 * Class rulesObjects
 * @package application\admin\controllers
 */
class rulesObjects extends applicationWeb\AControllers implements applicationWeb\IControllers
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