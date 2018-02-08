<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 12:24
 */

namespace application\admin\controllers\system\test;

use core\component\application as application;


/**
 * Class test
 * @package application\admin\controllers
 */
class test extends application\AControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * Инициализация
     */
    public function __construct()
    {
        self::redirect(self::$pageURL . '/field');
    }
}