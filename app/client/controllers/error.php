<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\client\controllers;

use core\components\applicationWeb\connectors;


/**
 * Class error
 * Контроллер ошибок
 * @package app\controllers
 */
class error extends connectors\AControllers implements connectors\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubUrl  =   0;

    /**
     * Инициализация
     */
    public function Init()
    {
        $this->template = $_SERVER['DOCUMENT_ROOT'] . 'app/client/theme/' . $this->page['template'];
        $this->content  = Array(
            '{NAME}'        =>  'Это 404 контроллер',
            '{TITLE}'       =>  $this->page['meta_title'],
            '{KEYWORDS}'    =>  $this->page['meta_keywords'],
            '{DESCRIPTION}' =>  $this->page['meta_description'],
        );
        header('HTTP/1.0 404 Not Found');
    }


}