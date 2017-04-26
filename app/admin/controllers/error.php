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
class error extends connectors\AControllers implements connectors\IControllers
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
        $text   =   '<h2>что-то не так</h2>'
                    . '<p>Возможно, запрашиваемая Вами страница была перенесена или удалена. 
                        Также возможно, Вы допустили небольшую опечатку при вводе адреса – такое случается даже с нами, 
                        поэтому еще раз внимательно проверьте</p>';
        $html     =   self::getRouter()->get('view')->replace(self::getTemplate('card_padding.tpl'),Array(
            'NAME' => 'Ой!',
            'TEXT'  =>  $text
        ));

        $this->content  = Array(
            'CONTENT'     =>  $html,
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );

    }

}
