<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 19:15
 */

namespace application\controllers\system\rules;

use application\model;
use \core\component\{
    application\AControllers
};


/**
 * Class rulesObjects
 * @package application\controllers
 */
class rulesObjects extends AControllers
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