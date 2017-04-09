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
        $this->content[$template] = Array(
            '{NAME}'        =>  'Это Basic контроллер',
            '{TEXT}'        =>  'Класс' . __CLASS__,
            '{TITLE}'       =>  $this->page['meta_title'],
            '{KEYWORDS}'    =>  $this->page['meta_keywords'],
            '{DESCRIPTION}' =>  $this->page['meta_description'],
        );
    }
}