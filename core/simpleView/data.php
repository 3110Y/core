<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.04.18
 * Time: 18:34
 */

namespace core\simpleView;


class data
{
    /**
     * Рендерит данные
     * @param array $data Данные
     * @param string $html HTML
     * @return string результат
     */
    public static function replace($html = '', array $data = Array()): string
    {
        $array  =   [];
        $array['{DEBUG}']   =   '<pre><code>' . print_r(self::htmlEntitiesArray($data), true) . '</code></pre>';
        foreach ($data as $key => $value) {
            $array["{{$key}}"] =  $value;
        }
        return strtr($html, $array);
    }

    private static function htmlEntitiesArray($data)
    {
        if (\is_array($data)) {
            $array = [];
            foreach ($data as $key => $value) {
                $array[$key] = self::htmlEntitiesArray($value);
            }
            return $array;
        }
        if (\is_string($data)) {
            return htmlentities($data);
        }
        return $data;

    }
}