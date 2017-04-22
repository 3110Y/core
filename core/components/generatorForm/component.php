<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:24
 */

namespace core\components\generatorForm;
use core\components\generator\connectors as generatorConnectors;
use core\components\component\connectors as componentConnectors;

class component extends generatorConnectors\AGenerator implements
    generatorConnectors\IGenerator,
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
     * @var string HTML
     */
    private static $html = '';


    /**
     * Конструирует
     * @param array $scheme схема
     * @return mixed|string|array результат
     */
    public static function construct($scheme)
    {
        for ($i = 0, $iMax = count($scheme); $i < $iMax; $i++) {
            $item = $scheme[$i];
            $name       =   (isset($scheme[$i]['gf-name']))     ?   $scheme[$i]['gf-name']      :   null;
            $value      =   (isset($scheme[$i]['gf-value']))    ?   $scheme[$i]['gf-value']     :   null;
            $handler    =   (isset($scheme[$i]['gf-handler']))  ?   $scheme[$i]['gf-handler']   :   null;
            unset($item[$scheme[$i]['gf-name']]);
            unset($item[$scheme[$i]['gf-value']]);
            unset($item[$scheme[$i]['gf-handler']]);
            if ($handler !== null) {
                self::$html .=  self::factory($handler, $item);
                continue;
            }
            if($name === null) {
                continue;
            }
            if($value !== null) {
                $value  =   self::construct(Array($scheme[$i]['gf-value']));
            }

            $param  =   Array();
            foreach ($item as $key => $value) {
                if(is_array($value)) {
                    $value = implode(' ', $value);
                }
                $param[] = "{$key}='{$value}'";
            }
            self::$html .= "<{$name} {$param}>{$value}</{$name}>";
        }
        self::$html = implode(PHP_EOL, $scheme);
        return self::$html;
    }



}