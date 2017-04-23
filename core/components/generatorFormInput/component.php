<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorFormInput;
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
    const NAME  =   'generatorFormInput';


    /**
     * Конструирует
     * @return mixed|string|array результат
     */
    public function construct()
    {
        $system =   isset($this->scheme['system'])        ?   $this->scheme['system']           :   Array();
        if(!empty($system)) {
            unset($this->scheme['system']);
        }
        $param  =   Array();
        foreach ($this->scheme as $key => $val) {
            if(is_array($val)) {
                $val = implode(' ', $val);
            }
            $param[] = "{$key}='{$val}'";
        }
        $param = implode(' ', $param);
        $system['value']    =   isset($system['value']) ?   $system['value']    :   '';
        return "<input type='{text}' name='{$system['field']}' value='{$system['value']}' {$param}>";
    }



}