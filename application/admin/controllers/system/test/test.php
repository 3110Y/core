<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 12:24
 */

namespace application\admin\controllers\system\test;

use core\application\AControllers;


/**
 * Class test
 * @package application\controllers
 */
class test extends AControllers
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