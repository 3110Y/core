<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 12:24
 */

namespace application\admin\controllers\system\test;

use core\component\application\handler\Web as applicationWeb;


/**
 * Class test
 * @package application\admin\controllers
 */
class test extends applicationWeb\AControllers implements applicationWeb\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * Инициализация
     */
    public function init()
    {
        self::redirect(self::$pageURL . '/field');
    }
}