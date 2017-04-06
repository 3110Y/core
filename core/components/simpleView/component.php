<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 15:39
 */

namespace core\components\simpleView;

/**
 * Class component
 * компонент шаблонизатора
 * @package core\components\simpleView
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
    const NAME  =   'simpleView';

    /**
     * @var string шаблон
     */
    private $template = '';

    /**
     * @var array данные
     */
    private $data   =   Array();

    /**
     * @var string результат
     */
    private $result   =   '';


    /**
     * Устанавливает шаблон
     * @param string $template шаблон
     */
    public function setTemplate($template)
    {
        $this->template =   $template;
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
        $this->result   =   render::run($this->template, $this->data);
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