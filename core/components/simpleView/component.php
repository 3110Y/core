<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 15:39
 */

namespace core\components\simpleView;
use core\components\component\connectors as componentsConnectors;
use core\components\view\connectors as viewConnectors;

/**
 * Class component
 * компонент шаблонизатора
 * @package core\components\simpleView
 */
class component extends viewConnectors\AView implements viewConnectors\IView, componentsConnectors\IComponent
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'simpleView';

    /**
     * Рендерит данные
     * @return string результат
     */
    public function run()
    {
        //TODO: проверка
        return self::replace($this->template . '.' .$this->extension, $this->data);
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
        if ($template !== false) {

            if (file_exists($template)) {
                $content = file_get_contents($template);
            } elseif ($_SERVER['DOCUMENT_ROOT'] . $template) {
                $content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $template);
            } else {
                //TODO: Проверка наличия шаблона
                die('Нет шаблона');
            }
        } else {
            $content    =   $html;
        }
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