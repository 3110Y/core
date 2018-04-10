<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.04.18
 * Time: 18:35
 */

namespace core\simpleView;

use  core\dir\dir;


class template
{
    /**
     * Отдает фрагмент
     * @param string $section раздел
     * @param string $html хтмл
     * @return mixed|string|bool результат
     */
    public static function cut($section, $html)
    {
        $pattern    =   "/{{$section}}(.*?){\\/{$section}}/is";
        preg_match($pattern , $html , $result);
        return $result[1] ?? false;
    }

    /**
     * Отдает фрагмент
     * @param string $section раздел
     * @param string $html хтмл
     * @return mixed|string|bool результат
     */
    public static function cutAll($section, $html)
    {
        $pattern    =   "/{{$section}}(.*?){\\/{$section}}/is";
        preg_match_all($pattern , $html , $result, PREG_PATTERN_ORDER);
        return $result[1] ?? false;
    }


    public static function toHTML($template)
    {
        if (file_exists($template)) {
            return file_get_contents($template);
        }
        if (file_exists(dir::getDR() . $template)) {
            $template   =   dir::getDR(true) . $template;
            return file_get_contents($template);
        }
        die('Нет шаблона: ' . $template);
    }

    /**
     * @param $content
     * @param bool $template
     * @param array $data
     * @return string
     */
    public static function include($content, $template = false, array $data = Array()): string
    {
        $array  =   Array();
        preg_match_all("/{include ['\"]?([a-z0-9\\/.\\-_]+)['\"]?}/i", $content, $output);
        if (!empty($output[1])) {
            $path   = substr($template,0, strrpos($template, '/') + 1);
            for ($i = 0, $iMax = \count($output[1]); $i < $iMax; $i++) {
                $file   =   $path . $output[1][$i];
                $array[$output[0][$i]]  =   self::replace($file, $data);
            }
            $content    =   strtr($content, $array);
        }
        return  $content;
    }
}
