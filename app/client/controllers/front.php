<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\client\controllers;

use core\components\applicationWeb\connectors;
use app\client\classes;


/**
 * Class front
 * Контроллер главной страницы
 * @package app\controllers
 */
class front extends connectors\AControllers implements connectors\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   0;

    /**
     * Инициализация
     */
    public function init()
    {
        $test       =   classes\session::getInstance()->exist('test');
        $testValue  =   '';
        if (!$test) {
            classes\session::getInstance()->set('test','test');
        } else {
            $testValue  =   classes\session::getInstance()->get('test');
        }
        $this->template =  $this->router->getTemplate($this->page['template']);
        $this->content  = Array(
            'NAME'        =>  'Это Фронтальный контроллер',
            'FOR'         =>  Array(
                Array(
                    'URL'     => 'TEST_1',
                    'NAME'    => 'TEST_1'
                ),
                Array(
                    'URL'     => 'TEST_2',
                    'NAME'    => 'TEST_2'
                ),
            ),
            'TEXT'        =>  $test   ?   "Ключ сессии test {$testValue}" :   'сессии test нет. Устанавливаем',
            'TITLE'       =>  $this->page['meta_title'],
            'KEYWORDS'    =>  $this->page['meta_keywords'],
            'DESCRIPTION' =>  $this->page['meta_description'],
        );
    }

}
