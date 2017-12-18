<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 13:13
 */

namespace application\admin\controllers\system\common;


use application\admin\model as model;
use \core\component\{
    application\handler\Web as applicationWeb
};


/**
 * Class settings
 * @package application\admin\controllers\system\common
 */
class settings extends applicationWeb\AControllers implements applicationWeb\IControllers
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
                'field'             =>  'meta_title',
                'label'             =>  'META Заголовок по умолчанию',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'meta_keywords',
                'label'             =>  'META Ключевые слова по умолчанию',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),
            Array(
                'type'              =>  'UKTextarea',
                'field'             =>  'meta_description',
                'label'             =>  'META Описание по умолчанию',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),

        );
        self::$content  =    model\CFormOne::generation($this, 'client_settings', 'Настройки', $field, 1);
    }
}