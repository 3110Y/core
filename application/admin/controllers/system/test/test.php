<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 12:24
 */

namespace application\admin\controllers\system\test;

use Core\{
    _application\controller\AController,
    router\route
};


/**
 * Class test
 * @package application\controllers
 */
class test extends AController
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * Инициализация
     * @param route $route
     */
    public function __construct(route $route)
    {
        self::redirect(self::$pageURL . '/field');
    }
}