<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 14:57
 */

namespace core\view;


use \core\dir\dir;


/**
 * Class template
 * @package core\view
 */
class template
{
    /**
     * @var array
     */
    private static $templateToPath = [];

    /**
     * @param string $template
     * @return bool|string
     */
    public static function toHTML(string $template): string
    {
        if (file_exists($template) && is_readable($template)) {
            $html = file_get_contents($template);
            if (false === $html) {
                return $html;
            }
        }
        if (file_exists(dir::getDR() . $template) && is_readable(dir::getDR() . $template)) {
            $template = dir::getDR(true) . $template;
            $html = file_get_contents($template);
            if (false === $html) {
                return $html;
            }
        }
        die('Нет шаблона: ' . $template);
    }

    /**
     * @param string $template
     * @return string
     */
    public static function getPath(string $template) : string
    {
        if (isset(self::$templateToPath[$template])) {
            return self::$templateToPath[$template];
        }
        $templatePosition   =   strrpos($template, DIRECTORY_SEPARATOR);
        if ($templatePosition === false) {
            return DIRECTORY_SEPARATOR;
        }
        $path               =   substr($template, 0, $templatePosition + 1);
        self::$templateToPath[$template]    =   $path;
        return self::$templateToPath[$template];
    }
}