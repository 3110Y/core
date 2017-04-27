<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 15:39
 */

namespace core\components\templateEngine\engine\simpleView;
use core\component\templateEngine as templateEngine;

/**
 * Class component
 * @package core\components\templateEngine\engine\simpleView
 */
class component extends templateEngine\AEngine implements templateEngine\IEngine
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;


    /**
     * Рендерит данные
     */
    public function run()
    {
        //TODO: проверка
        $this->result   =   self::replace($this->template . '.' .$this->extension, $this->data);
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
                $template   =   $_SERVER['DOCUMENT_ROOT'] . $template;
                $content = file_get_contents($template);
            } else {
                //TODO: Проверка наличия шаблона
                die('Нет шаблона');
            }
        } else {
            $content    =   $html;
        }
        preg_match_all("/{include ([a-z0-9\\/.]+)}/i", $content, $output);
        if (!empty($output[1])) {
            $path   = substr($template,0, strripos($template, '/') + 1);
            $array  =   Array();
            for ($i = 0, $iMax = count($output[1]); $i < $iMax; $i++) {
                $file   =   $path . $output[1][$i];
                $array[$output[0][$i]]  =   self::replace($file);
            }
            $content    =   strtr($content, $array);
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
     * @param mixed|string $template шаблон
     * @return string хтмл
     */
    public static function loop($tagEach, array $array, $html = '', $template = false)
    {
        if ($html == '' && $template != false) {
            $html   =   self::replace($template);
        }
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