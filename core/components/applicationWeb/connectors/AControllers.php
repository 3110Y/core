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
    public static $template = '';
    /**
     * @var array страница
     */
    public static $page = Array();
    /**
     * @var array URL
     */
    public static $URL = Array();
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   0;

    /**
     * @var mixed|null|object роутер
     */
    protected static $router = null;

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
    public static function getTemplate()
    {
        if (self::$template === '') {
            self::$template =  self::$router->getTemplate(self::$page['template']);
        }
        return self::$template;
    }

    /**
     * Задает страницу
     * @param array $page страница
     */
    public static function setPage(array $page)
    {
        self::$page = $page;
    }

    /**
     * Задает роутер
     * @param array $URL URL
     */
    public static function setURL(array $URL)
    {
        self::$URL = $URL;
    }

    /**
     * Задает Роутер
     * @param object $router роутер
     */
    public static function setRouter($router)
    {
        self::$router = $router;
    }

    /**
     * Отдает Роутер
     * @return object $router роутер
     */
    public static function getRouter($router)
    {
        //TODO: проверка
        return self::$router;
    }

    /**
     * Отдает Колличество подуровней
     * @return false|int|mixed
     */
    public static function getCountSubURL()
    {
        return self::$countSubURL;
    }


}
