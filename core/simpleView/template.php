<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.04.18
 * Time: 18:35
 */

namespace core\simpleView;

use core\dir\dir;


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
        $pattern = "/{{$section}}(.*?){\\/{$section}}/is";
        preg_match($pattern, $html, $result);
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
        $pattern = "/{{$section}}(.*?){\\/{$section}}/is";
        preg_match_all($pattern, $html, $result, PREG_PATTERN_ORDER);
        return $result[1] ?? false;
    }


    /**
     * @param string $template
     * @return bool|string
     */
    public static function toHTML(string $template): string
    {
        if (file_exists($template)) {
            $html = file_get_contents($template);
            if (false === $html) {
                return $html;
            }
        }
        if (file_exists(dir::getDR() . $template)) {
            $template = dir::getDR(true) . $template;
            $html = file_get_contents($template);
            if (false === $html) {
                return $html;
            }
        }
        die('Нет шаблона: ' . $template);
    }

    /**
     * @param $content
     * @param array $data
     * @param string $template
     * @return string
     */
    public static function include($content, array $data = Array(), string $template = ''): string
    {
        $path = substr($template, 0, strrpos($template, '/') + 1);
        $array = Array();
        preg_match_all("/{include ['\"]?([a-z0-9\\/.\\-_]+)['\"]?}/i", $content, $output);
        if (!empty($output[1])) {
            for ($i = 0, $iMax = \count($output[1]); $i < $iMax; $i++) {
                $file = $path . $output[1][$i];
                $array[$output[0][$i]] = simpleView::replace($file, $data, $template);
            }
            $content = strtr($content, $array);
        }
        return $content;
    }
}
