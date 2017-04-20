<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 17:03
 */

namespace core\components\simpleView;

/**
 * Class render
 * компонент рендера
 * @package core\components\simpleView
 */
class render
{
    /**
     * Рендерит данные
     * @param string $template шаблон
     * @param array $data Данные
     * @return string результат
     */
    public static function run($template, array $data = Array())
    {
        return self::replace($template, $data);
    }

    /**
     * Рендерит данные
     * @param mixed|bool|string $template шаблон
     * @param array $data Данные
     * @param string $html HTML
     * @return string результат
     */
    public static function replace($template = false, array $data = Array(), $html = '')
    {
        $content = file_get_contents($template . '.tpl');
        $array  =   Array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $content = self::loop("{$key}", $value, $content);
            } else {
                $array["{{$key}}"] =  $value;
            }
        }
        return strtr($content, $array);
    }

    /**
     * Переберает шаблоны
     * @param string $tagEach тег
     * @param array $array массив значений
     * @param string $html хтмл
     * @return string хтмл
     */
    public static function loop($tagEach, array $array, $html = '')
    {
        $cuteFragment = self::cut($tagEach, $html);

        $cuteResult = array();
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $cuteResult[] = self::replace(false, $value, $cuteFragment);
                }
            }
        }
        $cuteResult =   implode(PHP_EOL, $cuteResult);
        $reTemplate = preg_replace("/{{$tagEach}}.*?{\\/{$tagEach}}/is", $cuteResult, $html);
        return $reTemplate;
    }

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
        return isset($result[1]) ? $result[1] : false;
    }

}