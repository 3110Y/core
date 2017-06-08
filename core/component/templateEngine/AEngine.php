<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 16:06
 */

namespace core\component\templateEngine;

/**
 * Class AEngine
 * @package core\components\templateEngine
 */
abstract class AEngine
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
     * @var string рендер
     */
    protected $render   =   '';


    /**
     * Устанавливает шаблон
     * @param string $template шаблон
     */
    public function setTemplate($template)
    {
        $this->template =   $template;
    }

    /**
     * Устанавливает расширение шаблона
     * @param string $extension
     */
    public function setExtension($extension = 'tpl')
    {
        $this->extension    =   $extension;
    }

    /**
     * Устанавливает Данные
     * @param array $data Данные
     */
    public function setData(array $data = Array())
    {
        $this->data =   $data;
    }

    /**
     * Рендерит данные
     */
    public function run()
    {
        $render =   $this->render;
        $this->result   =   $render::run($this->template . '.' . $this->extension, $this->data);
    }

    /**
     * Отдает результат
     * @return string результат
     */
    public function get()
    {
        return $this->result;
    }

}