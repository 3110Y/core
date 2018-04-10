<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 15:39
 */

namespace core\simpleView;


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
    protected $extension = 'tpl';

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
        $this->result   =   self::replace($this->template . '.' .$this->extension, $this->data);
    }

    public static function replace($html, $data): string
    {

        $content    =   self::includeTemplate($content, $template, $data);
        $array['{DEBUG}']   =   '<pre>' . print_r($data, true) . '</pre>';
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $content    =   self::loop($key, $value, $content);
            } elseif (\is_bool($value)) {
                $content    =   self::condition($key, $value, $content);
            } else {
                $array["{{$key}}"] =  $value;
            }
        }
        return strtr($content, $array);
    }






}