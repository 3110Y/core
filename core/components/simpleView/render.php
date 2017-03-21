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
        $content = file_get_contents($template . '.tpl');
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $content = self::enumeration("<{$key}>", $value, $content);
            }
        }
        $data['{NOW_Y}'] = date('d');
        $data['{NOW_m}'] = date('m');
        $data['{NOW_d}'] = date('Y');
        return strtr($content, $data);
    }

    /**
     * Переберает шаблоны
     * @param string $tagEach тег
     * @param array $array массив значений
     * @param string $html хтмл
     * @return string хтмл
     */
    private static function enumeration($tagEach, array $array, $html = '')
    {
        $cuteFragment = self::cut($tagEach, $html);
        $cuteResult = array();
        if (count($array) > 0) {
            foreach ($array as $key => $val) {
                if (is_array($val)) {
                    $cuteResult[] = strtr($cuteFragment, $val);
                }
            }
        }
        $reTemplate = preg_replace('#<'.$tagEach.'>.*?</'.$tagEach.'>#is', implode("\r\n", $cuteResult), $html);
        return $reTemplate;
    }

    /**
     * Отдает фрагмент
     * @param string $section раздел
     * @param string $html хтмл
     * @return mixed|string|bool результат
     */
    protected static function cut($section, $html)
    {
        preg_match( '/'.$section.'(.*?)\\/'.$section.'/is' , $html , $result );
        return isset($result[1]) ? $result[1] : false;
    }

}