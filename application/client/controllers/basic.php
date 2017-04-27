<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.04.17
 * Time: 23:33
 */

namespace app\client\controllers;

use core\components\applicationWeb\connectors;
use app\client\classes;


/**
 * Class basic
 * Базовый контролер
 * @package app\client\controllers
 */
class basic extends connectors\AControllers implements connectors\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   false;

    /**
     * Инициализация
     */
    public function init()
    {
        $this->content  = Array(
            'NAME'        =>  'Это Basic контроллер',
            'TEXT'        =>  'Класс' . __CLASS__,
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
    }
}