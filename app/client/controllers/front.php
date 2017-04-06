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
     * @var array структура контента
     */
    public $content = Array();
    /**
     * @var array страница
     */
    public $page = Array();
    /**
     * @var array URL
     */
    public $url = Array();

    /**
     * front constructor.
     * @param array $page страница
     * @param array $url URL
     */
    public function __construct(array $page, array $url)
    {
        $this->page = $page;
        $this->url  = $url;
        $template    =  $_SERVER['DOCUMENT_ROOT'] . 'app/client/theme/' . $this->page['template'];

        $test       =   classes\session::getInstance()->exist('test');
        $testValue  =   '';
        if (!$test) {
            classes\session::getInstance()->set('test','test');
        } else {
            $testValue  =   classes\session::getInstance()->get('test');
        }
        $this->content[$template] = Array(
            '{NAME}'        =>  'Это Фронтальный контроллер',
            '{TEXT}'        =>  $test   ?   "Ключ сессии test {$testValue}" :   'сессии test нет. Устанавливаем',
            '{TITLE}'       =>  $this->page['meta_title'],
            '{KEYWORDS}'    =>  $this->page['meta_keywords'],
            '{DESCRIPTION}' =>  $this->page['meta_description'],
        );
    }

}
