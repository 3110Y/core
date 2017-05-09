<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.5.2017
 * Time: 18:13
 */

namespace core\component\application\handler\Web;
use core\core;

/**
 * Class AApplication
 * @package core\component\application\handler\Web
 */
abstract class AApplication
{
    /**
     * @var array  приложение
     */
    protected static $application = Array();
    /**
     * @var array js файлы
     */
    protected static $js = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );
    /**
     * @var array файлы
     */
    protected static $css = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );
    /**
     * @var mixed|null|object реестр
     */
    protected static $registry = array();
    /**
     * @var array структура контента
     */
    protected static $content = Array();
    /**
     * @var array URL
     */
    protected static $URL = Array();
    /**
     * @var array структура приложения
     */
    protected static $structure = array();
    /**
     * @var array текущая страница
     */
    protected static $page = Array();
    /**
     * @var array страница для ошибок
     */
    protected static $pageError = Array();
    /**
     * @var string шаблон
     */
    protected static $template = '';


    /**
     * отдает шаблон из темы
     * @param string $template шаблон
     * @return string шаблон
     */
    protected static function getTemplate(string $template): string
    {
        $path   =   self::$application['path'];
        $theme   =   self::$application['theme'];
        return "/application/{$path}/theme/{$theme}/{$template}";
    }

    /**
     * Задает JS
     * @param string $file файл
     * @param bool $isTopPosition позиция top|bottom
     * @param bool $isUnique уникальность
     */
    protected static function setJs(string $file, bool $isTopPosition = false, bool $isUnique = true)
    {
        $position   =   $isTopPosition   ?   'top'   :   'bottom';
        if ($isUnique) {
            $name   =   basename($file);
            if (!isset(self::$js['top'][$name]) && !isset(self::$js['bottom'][$name])) {
                self::$js[$position][$name] = $file;
            }
        } else {
            self::$js[$position][] =   $file;
        }
    }

    /**
     * Задает CSS
     * @param string $file файл
     * @param bool $isTopPosition позиция top|bottom
     * @param bool $isUnique уникальность
     */
    protected static function setCss(string $file, bool $isTopPosition = true, bool $isUnique = true)
    {
        $position   =   $isTopPosition   ?   'top'   :   'bottom';
        if ($isUnique) {
            $name   =   basename($file);
            if (!isset(self::$css['top'][$name]) && !isset(self::$css['bottom'][$name])) {
                self::$css[$position][$name] = $file;
            }
        } else {
            self::$css[$position][] =   $file;
        }
    }

    /**
     * Отдает CSS
     * @param bool $isTopPosition позиция top|bottom
     * @return string CSS
     */
    protected static function getCSS($isTopPosition = true): string
    {
        $position   =   $isTopPosition   ?   'top'   :   'bottom';
        $css   =   array_diff(array_unique(self::$css[$position]), array());
        $text   =   '<!-- AUTO CSS -->';
        foreach ($css as $key   =>  $file) {
            $location       = false;
            $includeFile    =   $file;
            if (file_exists($includeFile)) {
                $location   =   $includeFile;
            } elseif (file_exists($includeFile . '.css')) {
                $includeFile    .=  '.css';
                $location       =  $includeFile . '.css';
            } elseif (file_exists(core::getDR() . $includeFile)) {
                $location       =   core::getDR() . $includeFile;
            } elseif (file_exists(core::getDR() . $includeFile . '.css'))  {
                $includeFile    .= '.css';
                $location       = core::getDR() . $includeFile . '.css';
            } elseif (file_exists(self::getTemplate($includeFile)))  {
                $includeFile    = self::getTemplate($includeFile);
                $location       = $includeFile;
            }  elseif (file_exists(self::getTemplate($includeFile . '.css')))  {
                $includeFile   = self::getTemplate($includeFile . '.css');
                $location       = $includeFile;
            } elseif (file_exists(core::getDR() . self::getTemplate($includeFile)))  {
                $includeFile   = self::getTemplate($includeFile);
                $location       = core::getDR() . $includeFile;
            } elseif (file_exists(core::getDR() . self::getTemplate($includeFile . '.css'))) {
                $includeFile    = self::getTemplate($includeFile . '.css');
                $location       = core::getDR() . $includeFile;
            }
            if ($location !== false) {
                $includeFile .= '?' . date ("YmdHis.", filemtime($location));
            } else {
                $includeFile .= '?none';
            }
            $text   .=  "<link rel='stylesheet' type='text/css' href='{$includeFile}'>";
        }
        $text   .=   '<!-- AUTO CSS-->';
        return $text;
    }

    /**
     * Отдает JS
     * @param bool $isTopPosition позиция top|bottom
     * @return string JS
     */
    protected static function getJS($isTopPosition = true)
    {
        $position   =   $isTopPosition   ?   'top'   :   'bottom';
        $js   =   array_diff(array_unique(self::$js[$position]), array());
        $text   =   '<!-- AUTO JS-->';
        foreach ($js as $key   =>  $file) {
            $location       = false;
            $includeFile    =   $file;
            if (file_exists($includeFile)) {
                $location   =   $includeFile;
            } elseif (file_exists($includeFile . '.js')) {
                $includeFile   = $includeFile . '.js';
                $location   =   $includeFile . '.js';
            } elseif (file_exists(core::getDR() . $includeFile)) {
                $location   =   core::getDR() . $includeFile;
            } elseif (file_exists(core::getDR() . $includeFile . '.js'))  {
                $includeFile .= '.js';
            } elseif (file_exists(self::getTemplate($includeFile)))  {
                $includeFile   = self::getTemplate($includeFile);
                $location   = $includeFile;
            }  elseif (file_exists(self::getTemplate($includeFile . '.js')))  {
                $includeFile   = self::getTemplate($includeFile . '.js');
                $location   = $includeFile;
            } elseif (file_exists(core::getDR() . self::getTemplate($includeFile)))  {
                $includeFile   = self::getTemplate($includeFile);
                $location   = core::getDR() . $includeFile;
            } elseif (file_exists(core::getDR() . self::getTemplate($includeFile . '.js'))) {
                $includeFile   = self::getTemplate($includeFile . '.js');
                $location   = core::getDR() . $includeFile;
            }
            if ($location !== false) {
                $includeFile .= '?' . date ("YmdHis.", filemtime($location));
            } else {
                $includeFile .= '?none';
            }
            $text   .=  "<script type='text/javascript' src='{$includeFile}'></script>";
        }
        $text   .=   '<!-- AUTO JS-->';
        return $text;
    }

    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected static function redirect($url, $isExternal = false)
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol   =   isset($_SERVER['HTTP_X_FORWARDED_PROTO'])   ?   $_SERVER['HTTP_X_FORWARDED_PROTO']  :   'http';
            $url        =   $protocol . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }

    /**
     * задает ключь и значение реестра
     * @param string $key ключ
     * @param mixed|string|object $class класс
     * @return boolean
     */
    protected static function set($key, $class)
    {
        if (isset(self::$registry[$key])) {
            return false;
        }
        return self::$registry[$key] = $class;
    }

    /**
     * Отдает значение ключа реестра
     * @param string $key ключ
     * @return mixed|null|object рендер
     */
    protected static function get($key)
    {
        //TODO: обработка ошибок
        if (isset(self::$registry[$key])) {
            return self::$registry[$key];
        }
        return false;
    }
}