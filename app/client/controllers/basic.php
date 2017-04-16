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
    protected static $countSubUrl  =   false;

    /**
     * Инициализация
     */
    public function Init()
    {
        $this->template = $_SERVER['DOCUMENT_ROOT'] . 'app/client/theme/' . $this->page['template'];
        $this->content  = Array(
            '{NAME}'        =>  'Это Basic контроллер',
            '{TEXT}'        =>  'Класс' . __CLASS__,
            '{TITLE}'       =>  $this->page['meta_title'],
            '{KEYWORDS}'    =>  $this->page['meta_keywords'],
            '{DESCRIPTION}' =>  $this->page['meta_description'],
        );
    }
}