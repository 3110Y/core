<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorFormText;
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
    public function construct($scheme)
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
        return "<textarea name='{$system['field']}' {$param}>{$system['value']}</textarea>";
    }



}