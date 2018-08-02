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
        [$title, $description, $keywords] = model\MetaData::getCFormFieldsData();
        $field     =   Array(
            $title,
            $description,
            $keywords
        );
        model\CFormDefault::config($this, 'client_settings', 'Настройки', $field);
        model\CFormDefault::setOne();
        self::$content  =   model\CFormDefault::generation($this);
    }
}