<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace application\admin\controllers\system\common;


use core\component\application as application;


/**
 * Class error
 * @package app\admin\controllers
 */
class error extends application\AControllers implements application\IControllers
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
        self::$content['CONTENT']  =    'Ошибка';
    }

}
