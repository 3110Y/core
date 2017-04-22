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

}