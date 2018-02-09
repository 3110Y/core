<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 17.01.2018
 * Time: 15:04
 */

namespace core\component\resources;


use  core\component\dir\dir;
/**
 * Class resources
 * @package core\component\resources
 */
class resources
{
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
     * Задает JS
     * @param string $file файл
     * @param bool $isTopPosition позиция top|bottom
     * @param bool $isUnique уникальность
     */
    public static function setJs(string $file, bool $isTopPosition = false, bool $isUnique = true)
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
    public static function setCss(string $file, bool $isTopPosition = true, bool $isUnique = true)
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
     * Задает множество JS
     * @param array $scripts скрипты
     */
    public static function addJs(array $scripts = Array())
    {
        foreach ($scripts as $script) {
            self::setJs($script['file'], $script['isTopPosition'], $script['isUnique']);
        }
    }

    /**
     * Задает множество CSS
     * @param array $style стили
     */
    public static function addCss(array $style = Array())
    {
        foreach ($style as $css) {
            self::setCss($css['file'], $css['isTopPosition'], $css['isUnique']);
        }
    }

    /**
     * Отдает CSS
     * @param bool $isTopPosition позиция top|bottom
     * @return string CSS
     */
    public static function getCSS($isTopPosition = true): string
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
            } elseif (file_exists(dir::getDR() . $includeFile)) {
                $location       =   dir::getDR() . $includeFile;
            } elseif (file_exists(dir::getDR() . $includeFile . '.css'))  {
                $includeFile    .= '.css';
                $location       = dir::getDR() . $includeFile . '.css';
            }
            if ($location !== false) {
                $includeFile .= '?' . date ("YmdHis", filemtime($location));
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
    public static function getJS($isTopPosition = true): string
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
            } elseif (file_exists(dir::getDR() . $includeFile)) {
                $location   =   dir::getDR() . $includeFile;
            } elseif (file_exists(dir::getDR() . $includeFile . '.js'))  {
                $includeFile .= '.js';
            }
            if ($location !== false) {
                $includeFile .= '?' . date ("YmdHis", filemtime($location));
            } else {
                $includeFile .= '?none';
            }
            $text   .=  "<script src='{$includeFile}'></script>";
        }
        $text   .=   '<!-- AUTO JS-->';
        return $text;
    }
}