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
 * @package core\component\application\handler\Web
 */
abstract class AControllers extends AApplication
{
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
     * Задает подстраницы
     * @param array $subURL подстраницы
     */
    public static function setSubURL(array $subURL)
    {
        self::$subURL = $subURL;
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
     * Отдает URL
     * @param mixed|int|boolean $level уровень URL
     * @return mixed|string|boolean URL
     */
    protected static function getURL($level = false)
    {
        if ($level === false) {
            return self::$URL;
        }
        return isset(self::$URL[$level])  ?   self::$URL[$level]  :   false;
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
