<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorFormSelect;
use core\components\generatorForm\connectors as generatorFormConnectors;
use core\components\component\connectors as componentConnectors;

/**
 * Генератор форм
 * Class component
 * @package core\components\generatorForm
 */
class component extends generatorFormConnectors\AGeneratorForm implements
    generatorFormConnectors\IGeneratorForm,
    componentConnectors\IComponent
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'generatorFormText';


    /**
     * Конструирует
     * @param array $scheme схема
     * @return mixed|string|array результат
     */
    public static function construct($scheme)
    {
        $system =   isset($scheme['system'])        ?   $scheme['system']           :   Array();
        if(!empty($system)) {
            unset($scheme['system']);
        }
        $param  =   Array();
        foreach ($scheme as $key => $val) {
            if(is_array($val)) {
                $val = implode(' ', $val);
            }
            $param[] = "{$key}='{$val}'";
        }
        $param = implode(' ', $param);
        $system['value']    =   isset($system['value']) ?   $system['value']    :   '';
        $system['list']     =   isset($system['list'])  ?   $system['list']     :   Array();
        $list   =   '';
        foreach($system['list'] as $k => $v) {
            if (isset($val['v'])) {
                $key    =    $v['v'];
            } elseif (isset($val['val'])) {
                $key    =    $v['val'];
            } elseif(isset($val['value'])) {
                $key    =    $v['value'];
            } elseif(isset($val[0])) {
                $key    =    $v['value'];
            } else {
                $key    =   $k;
            }
            if (isset($val['n'])) {
                $name    =    $v['n'];
            } elseif(isset($val['name'])) {
                $name    =    $v['name'];
            } elseif(is_string($v)) {
                $name    =   $v;
            } elseif(isset($val[1])) {
                $name    =    $v['value'];
            }
            $selected   =   $system['value'] == $key    ?   'selected'  :   '';
            $list   .= "<option value='{$key}' {$selected}>{$name}</option>";
        }
        return "<select name='{$system['field']}' {$param}>{$system['value']}</select>";
    }



}