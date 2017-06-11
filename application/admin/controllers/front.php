<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace application\admin\controllers;


use core\component\application\handler\Web as applicationWeb;


/**
 * Class front
 * @package application\admin\controllers
 */
class front extends applicationWeb\AControllers implements applicationWeb\IControllers
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
        self::$content['CONTENT']  =    'Привет';
    }

}
