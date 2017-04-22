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
     * @param array $scheme схема
     * @return mixed|string|array результат
     */
    public static function construct($scheme)
    {

    }



}