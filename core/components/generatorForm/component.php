<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorForm;
use core\components\generatorForm\connectors as connectors;
use core\components\component\connectors as componentConnectors;

class component extends connectors\AGenerator implements
    connectors\IGenerator,
    componentConnectors\IComponent
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'PDO';


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
            $system =   isset($scheme[$i]['system'])        ?   $scheme[$i]['system']       :   null;
            $tag    =   isset($scheme[$i]['tag'])           ?   $scheme[$i]['tag']           :   null;
            if($system !== null) {
                unset($item['system']);
            }
            if($tag !== null) {
                unset($item['tag']);
            }

            if (isset($system['handler'])) {
                $html .=  self::factory($system['handler'], $item);
                continue;
            }
            if($tag === null) {
                continue;
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
        return $html;
    }

    public static function save($scheme)
    {

    }

}