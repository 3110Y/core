<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\admin\controllers;

use core\components\applicationWeb\connectors;


/**
 * Class front
 * Контроллер главной страницы
 * @package app\admin\controllers
 */
class front extends connectors\AControllers implements connectors\IControllers
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
        $this->content  = Array(
            'CONTENT'     =>  'Делать',
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
    }

}
