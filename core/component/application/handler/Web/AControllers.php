<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\component\application\handler\Web;

/**
 * Class AControllers
 * @package core\components\application\handler\Web
 */
abstract class AControllers extends AApplication
{


    /**
     * @var array страница
     */
    public static $page = Array();
    /**
     * @var array URL
     */
    protected static $URL = Array();
    /**
     * @var array URL путь
     */
    protected static $pageURL= Array();
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   0;
    /**
     * @var array подуровни
     */
    protected static $subURL  =   Array();

    /**
     * Отдает структуру контента
     * @return array структура контента
     */
    public function getContent()
    {
        return $this->content;
    }



    /**
     * Задает подстраницы
     * @param array $subURL подстраницы
     */
    public static function setSubURL(array $subURL)
    {
        self::$subURL = $subURL;
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
     * Задает URL страницы
     * @param string $URL URL
     */
    public static function setPageURL($URL)
    {
        self::$pageURL = $URL;
    }

    /**
     * Задает URL
     * @param array $URL URL
     */
    public static function setURL(array $URL)
    {
        self::$URL = $URL;
    }

    /**
     * Отдает URL
     * @param mixed|int|boolean $level уровень URL
     * @return mixed|string|boolean URL
     */
    public static function getURL($level = false)
    {
        if ($level === false) {
            return self::$URL;
        }
        return isset(self::$URL[$level])  ?   self::$URL[$level]  :   false;
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
     * Отдает Колличество подуровней
     * @return false|int|mixed
     */
    public static function getCountSubURL()
    {
        return self::$countSubURL;
    }



}
