<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:23
 */

namespace core\components\generatorForm\connectors;


/**
 * Class IGenerator
 * Коннектор генератора
 * @package core\components\generator\connectors
 */
interface IGeneratorForm
{
    /**
     * Конструирует
     * @return mixed|string|array результат
     */
    public function construct();





}