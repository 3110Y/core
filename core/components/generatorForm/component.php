<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorForm;
use core\components\component\connectors as componentConnectors;

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
     * Конструирует
     * @param array $scheme схема
     * @return mixed|string|array результат
     */
    public static function construct($scheme)
    {
        $html   =   '';
        for ($i = 0, $iMax = count($scheme); $i < $iMax; $i++) {
            $item   = $scheme[$i];
            if (isset($item['system']['handler'])) {
                $html .=  self::factory($item['system']['handler'])::construct($scheme[$i]);
                continue;
            } elseif (is_string($item)) {
                $html .= $item;
            } elseif (!isset($scheme[$i]['tag'])) {
                continue;
            } else {

                $html .= self::constructHTML($item);
            }
        }
        return $html;
    }

    /**
     * фабрика
     * @param string $handler имя
     * @return string результат
     */
    private static function factory($handler)
    {
        //TODO: проверка
        return "\\core\\components\\generatorForm{$handler}\\component";
    }

    /**
     * Конструирует HTML
     * @param array $item схемв
     * @return string HTML
     */
    private static function constructHTML($item)
    {
        $html   =   '';
        $system =   isset($item['system'])        ?   $item['system']           :   Array();
        $tag    =   isset($item['tag'])           ?   $item['tag']              :   null;
        if(!empty($system)) {
            unset($item['system']);
        }
        if($tag !== null) {
            unset($item['tag']);
        }
        if(isset($item['children']) && is_array($item['children'])) {
            $children  =   self::construct($item['children']);
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
            $html .= "<{$tag} {$param}>";
        } else {
            $html .= "<{$tag} {$param} > {$children} </{$tag}>";
        }
        return $html;
    }

}