<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorForm;
use core\components\component\connectors as componentConnectors;
use core\components\generatorForm\connectors as generatorFormConnectors;

/**
 * Генератор форм
 * Class component
 * @package core\components\generatorForm
 */
class component extends componentConnectors\AComponent implements componentConnectors\IComponent
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'generatorForm';
    /**
     * @var array Схема
     */
    private $scheme = array();
    /**
     * @var array Данные
     */
    private $data = array();
    /**
     * @var array Конфигурация
     */
    private $config = array();
    /**
     * @var string html
     */
    private $html = '';
    /**
     * @var array JS
     */
    private $js =   Array();
    /**
     * @var array CSS
     */
    private $css =   Array();
    /**
     * @var array поля
     */
    private $fields = array();

    public function __construct()
    {
    }

    /**
     * Устанавливает Схему
     * @param array $scheme Схема
     */
    public function setScheme($scheme)
    {
        $this->scheme   =   $scheme;
    }

    /**
     * Устанавливает Данные
     * @param array $data Данные
     */
    public function setData($data)
    {
        $this->data   =   $data;
    }

    /**
     * Устанавливает Конфигурацию
     * @param array $config Конфигурация
     */
    public function setConfig($config)
    {
        $this->config   =   $config;
    }

    /**
     * Запуск
     */
    public function run()
    {
        $this->construct($this->scheme);
        $array = Array();
        foreach ($this->fields as $key => $value) {
            $array[$key]    =  $value->construct();
        }
        $this->html =   strtr($this->html, $array);
    }

    /**
     * Отдает HTML
     * @return string HTML
     */
    public function getHTML()
    {
        return $this->html;
    }

    /**
     * Отдает JS
     * @return array JS
     */
    public function getJS()
    {
        return $this->js;
    }

    /**
     * Отдает CSS
     * @return array CSS
     */
    public function getCSS()
    {
        return $this->css;
    }

    /**
     * сохраняет в db
     */
    public function save()
    {

    }

    /**
     * получает из db
     */
    private function load()
    {

    }



    /**
     * Конструирует
     * @param array $scheme схема
     */
    private function construct($scheme)
    {
        for ($i = 0, $iMax = count($scheme); $i < $iMax; $i++) {
            $item   = $scheme[$i];
            if (isset($item['system']['handler'])) {
                $this->html .=  $this->factory($item['system']['handler'], $scheme[$i]);
                continue;
            } elseif (is_string($item)) {
                $this->html .= $item;
            } elseif (!isset($scheme[$i]['tag'])) {
                continue;
            } else {
                $this->html .= $this->constructHTML($item);
            }
        }
    }

    /**
     * фабрика
     * @param string $handler имя
     * @param array $scheme схема
     * @return string результат
     */
    private function factory($handler, $scheme)
    {
        //TODO: проверка
        $handler    =   "\\core\\components\\generatorForm{$handler}\\component";
        $handler    =   new $handler();
        $handler->setScheme($scheme);
        return $this->setFields($handler);
    }

    /**
     * Устанавливает обьект поля
     * @param object $object обьект
     * @return bool|string ключ
     */
    private function setFields($object)
    {
        if ($object  instanceof generatorFormConnectors\IGeneratorForm) {
            $key    =   '{' . md5(spl_object_hash($object)) . '}';
            $this->fields[$key]    =   $object;
            return $key;
        }
        return false;
    }

    /**
     * Конструирует HTML
     * @param array $item схемв
     */
    private function constructHTML($item)
    {
        $system =   isset($item['system'])        ?   $item['system']           :   Array();
        $tag    =   isset($item['tag'])           ?   $item['tag']              :   null;
        if(!empty($system)) {
            unset($item['system']);
        }
        if($tag !== null) {
            unset($item['tag']);
        }
        if(isset($item['children']) && is_array($item['children'])) {
            $children  =   $this->construct($item['children']);
            unset($item['children']);
        } elseif(isset($item['children'])){
            $children   =   $item['children'];
            unset($item['children']);
        } else {
            $children   =   null;
        }
        $param  =   Array();
        foreach ($item as $key => $val) {
            if(is_array($val)) {
                $val = implode(' ', $val);
            }
            $param[] = "{$key}='{$val}'";
        }
        $param = implode(' ', $param);
        if ($children === null) {
            $this->html .= "<{$tag} {$param}>";
        } else {
            $this->html .= "<{$tag} {$param} > {$children} </{$tag}>";
        }
    }

}