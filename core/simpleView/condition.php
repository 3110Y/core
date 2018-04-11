<?php
/**
 * Created by PhpStorm.
 * User: Roman Gaevoy
 * Date: 10.04.18
 * Time: 18:34
 */

namespace core\simpleView;


class condition
{
    /**
     * @param $key
     * @param $value
     * @param $content
     * @return string
     */
    public static function render($key, $value, $content): string
    {
        $tagEach = "if {$key}";
        $cuteFragment = template::cut($tagEach, $content);
        if (!$value) {
            $cuteFragment = '';
        }
        return preg_replace("/{if{$key}}.*?{\\/{$key}}/is", $cuteFragment, $content);
    }
}