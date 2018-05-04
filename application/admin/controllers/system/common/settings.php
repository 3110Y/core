<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 13:13
 */

namespace application\admin\controllers\system\common;


use application\admin\model;
use \core\component\{
    application\AControllers
};


/**
 * Class settings
 * @package application\admin\controllers\system\common
 */
class settings extends AControllers
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
        model\CFormDefault::config($this, 'client_settings', 'Настройки', $field);
        model\CFormDefault::setOne();
        self::$content  =   model\CFormDefault::generation($this);
    }
}