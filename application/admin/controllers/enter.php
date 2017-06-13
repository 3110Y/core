<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 5:25
 */

namespace application\admin\controllers;

use \core\component\{
    application\handler\Web as applicationWeb
};


class enter extends applicationWeb\AControllers implements applicationWeb\IControllers
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

    }
}