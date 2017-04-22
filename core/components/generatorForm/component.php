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
            $system =   isset($scheme[$i]['system'])        ?   $scheme[$i]['system']           :   Array();
            $tag    =   isset($scheme[$i]['tag'])           ?   $scheme[$i]['tag']              :   null;
            if(!empty($system)) {
                unset($item['system']);
            }
            if($tag !== null) {
                unset($item['tag']);
            }
            if (isset($system['handler'])) {
                $html .=  self::factory($system['handler'])::construct($item);
                continue;
            } elseif (is_string($item)) {
                $html .= $item;
            } elseif ($tag === null) {
                continue;
            } else {
                $html .= self::constructHTML($item, $tag);
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
     * @param null $tag тег
     * @return string HTML
     */
    private static function constructHTML($item, $tag = null)
    {
        $html   =   '';
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