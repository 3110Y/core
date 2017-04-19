<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\components\applicationWeb\connectors;

/**
 * Class controllers
 * Коннектор контроллера Web приложения
 * @package core\connectors\app
 */
abstract class AControllers
{
    /**
     * @var array структура контента
     */
    public $content = Array();
    /**
     * @var string шаблон
     */
    public $template = '';
    /**
     * @var array страница
     */
    public $page = Array();
    /**
     * @var array URL
     */
    public $URL = Array();

    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   0;


    /**
     * Отдает структуру контента
     * @return array структура контента
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Отдает Шаблон
     * @return string шаблон
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Задает страницу
     * @param array $page страница
     */
    public function setPage(array $page)
    {
        $this->page = $page;
    }

    /**
     * Задает URL
     * @param array $URL URL
     */
    public function setURL(array $URL)
    {
        $this->URL = $URL;
    }

    /**
     * Отдает Колличество подуровней
     * @return false|int|mixed
     */
    public function getCountSubURL()
    {
        return self::$countSubURL;
    }


}
