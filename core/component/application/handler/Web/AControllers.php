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
     * @var mixed|null|object роутер
     */
    protected static $router = null;
    /**
     * @var array шаблон
     */
    protected static $js = Array();
    /**
     * @var array шаблон
     */
    protected static $css = Array();


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
     * @param  string $template шаблон
     * @return string шаблон
     */
    public static function getTemplate($template = '')
    {
        if($template != '') {
            return self::$router->getTemplate($template);
        } elseif (self::$template === '') {
            self::$template =  self::$router->getTemplate(self::$page['template']);
        }
        return self::$template;
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
     * Отдает Роутер
     * @return object $router роутер
     */
    public static function getRouter()
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

    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected  static function redirect($url, $isExternal = false)
    {
        if ($isExternal === false) {
            $url    =   $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }

    public function getJS()
    {
        self::$js   =   array_unique(self::$js);
        self::$js   =   array_diff(self::$js, array());
        $js         =   '';
        foreach (self::$js as $file) {
            if (!file_exists($file)) {
                if (file_exists(self::getTemplate($file))) {
                    $file   =   self::getTemplate($file);
                } elseif (file_exists($file . '.js')) {
                    $file   =   $file . '.js';
                } elseif (file_exists(self::getTemplate($file . '.js'))) {
                    $file   =   self::getTemplate($file . '.js');
                } elseif (file_exists(self::getTemplate('js/' . $file))) {
                    $file   =   self::getTemplate('js/' . $file);
                } elseif (file_exists('js/' . $file . '.js')) {
                    $file   =   'js/' . $file . '.js';
                } elseif (file_exists(self::getTemplate('js/' . $file . '.js'))) {
                    $file   =   self::getTemplate('js/' . $file . '.js');
                }  else {
                    $file .= '?none';
                }
            }
            $js .=   "<script src='{$file}'></script>";
        }
        return $js;
    }

    public function getCSS()
    {
        self::$css   =   array_unique(self::$css);
        self::$css   =   array_diff(self::$css, array());
        $css         =   '';
        foreach (self::$css as $file) {
            if (!file_exists($file)) {
                if (file_exists(self::getTemplate($file))) {
                    $file = self::getTemplate($file);
                } elseif (file_exists($file . '.css')) {
                    $file = $file . '.css';
                } elseif (file_exists(self::getTemplate($file . '.css'))) {
                    $file = self::getTemplate($file . '.css');
                } elseif (file_exists(self::getTemplate('css/' . $file))) {
                    $file = self::getTemplate('css/' . $file);
                } elseif (file_exists('css/' . $file . '.css')) {
                    $file = 'css/' . $file . '.css';
                } elseif (file_exists(self::getTemplate('css/' . $file . '.css'))) {
                    $file = self::getTemplate('css/' . $file . '.css');
                } else {
                    $file .= '?none';
                }
            }
            $css .=   "<link rel='stylesheet' type='text/css' href='{$file}'>";
        }
        return $css;
    }
}
