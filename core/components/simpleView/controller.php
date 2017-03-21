<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:40
 */

namespace core\components\simpleView;

/**
 * Class controller
 * компонент контроллер шаблонизатора
 * @package core\components\simpleView
 */
class controller
{
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
     * @return string результат
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