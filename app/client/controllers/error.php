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
     * error constructor.
     * @param array $page страница
     * @param array $url URL
     */
    public function __construct(array $page, array $url)
    {

        $this->page = $page;
        $this->url  = $url;
        $template    =  $_SERVER['DOCUMENT_ROOT'] . '/app/theme/' . $this->page['template'];
        $this->content[$template] = Array(
            '{NAME}'        =>  'Это 404 контроллер',
            '{TITLE}'       =>  $this->page['meta_title'],
            '{KEYWORDS}'    =>  $this->page['meta_keywords'],
            '{DESCRIPTION}' =>  $this->page['meta_description'],
        );
        header('HTTP/1.0 404 Not Found');
    }


}