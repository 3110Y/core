<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 15:02
 */

namespace core\components\view;

/**
 * Class component
 * Базовый Компонент шаблонизатора
 * @package core\components\view
 */
class component
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'view';
    /**
     * @var string шаблон
     */
    private $template = '';
    /**
     * @var string расширение шаблона
     */
    private $extension = '';
    /**
     * @var array данные
     */
    private $data   =   Array();
    /**
     * @var string результат
     */
    private $result   =   '';
    /**
     * @var string рендер
     */
    private $render   =   '';


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
    public function render()
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

    /**
     * Устанавливает шаблонизатор
     * @param string $render рендер
     */
    public function setRender($render)
    {
        $this->render   =   "core\\components\\{$render}\\component";
    }

    /**
     * Отдает шаблонизатор
     * @return string рендер
     */
    public function getRender()
    {
        return $this->render;
    }

}