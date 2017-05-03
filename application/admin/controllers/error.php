<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\admin\controllers;


use core\component\application\handler\Web as handlerWeb;


/**
 * Class error
 * @package app\admin\controllers
 */
class error extends handlerWeb\AControllers implements handlerWeb\IControllers
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
        header('HTTP/1.0 404 Not Found');
        self::$content['CONTENT']  =    '404';

    }

}
