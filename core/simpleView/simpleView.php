<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 15:39
 */

namespace core\simpleView;

use  core\dir\dir;

/**
 * Class component
 * @package core\templateEngine\engine\simpleView
 */
class simpleView
{
    /**
     * @var string шаблон
     */
    protected $template = '';

    /**
     * @var string расширение шаблона
     */
    protected $extension = '';

    /**
     * @var array данные
     */
    protected $data   =   Array();

    /**
     * @var string результат
     */
    protected $result   =   '';

    /**
     * Отдает результат
     * @return string результат
     */
    public function get(): string
    {
        return $this->result;
    }

    /**
     * Устанавливает шаблон
     * @param string $template шаблон
     */
    public function setTemplate($template): void
    {
        $this->template =   $template;
    }

    /**
     * Устанавливает расширение шаблона
     * @param string $extension
     */
    public function setExtension($extension = 'tpl'): void
    {
        $this->extension    =   $extension;
    }

    /**
     * Устанавливает Данные
     * @param array $data Данные
     */
    public function setData(array $data = Array()): void
    {
        $this->data =   $data;
    }


    /**
     * Рендерит данные
     */
    public function run(): void
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
    public static function replace($template = false, array $data = Array(), $html = ''): string
    {
        if ($template !== false) {
            if (file_exists($template)) {
                $content = file_get_contents($template);
            } elseif (file_exists(dir::getDR() . $template)) {
                $template   =   dir::getDR(true) . $template;
                $content = file_get_contents($template);
            } else {
                //TODO: Проверка наличия шаблона
                die('Нет шаблона: ' . $template);
            }
        } else {
            $content    =   $html;
        }
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
	    $array['{DEBUG}']   =   '<pre>' . print_r($data, true) . '</pre>';
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
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
    public static function loop($tagEach, array $array, $html = '', $template = false): string
    {
        if ($html === '' && $template !== false) {
            $html   =   self::replace($template);
        }
        $cuteFragment = self::cut($tagEach, $html);

        $cuteResult = array();
        if (\count($array) > 0) {
            foreach ($array as $key => $value) {
                if (\is_array($value)) {
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
        return $result[1] ?? false;
    }
}