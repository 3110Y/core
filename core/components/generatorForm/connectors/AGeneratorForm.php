<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 22.4.2017
 * Time: 20:22
 */

namespace core\components\generatorForm\connectors;
use core\components\component\connectors as componentConnectors;


/**
 * Class AGenerator
 * Коннектор генератора
 * @package core\components\generator\connectors
 */
abstract class AGeneratorForm extends componentConnectors\AComponent implements componentConnectors\IComponent
{
    /**
     * @var array Схема
     */
    private $scheme = array();

    /**
     * Устанавливает Схему
     * @param array $scheme Схема
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }
}