<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 23:24
 */

namespace core\components\applicationWeb;
use core\components\application\connectors as applicationConnectors;
use core\components\component\connectors as componentsConnectors;

/**
 * Class component
 * Компонент Web приложения
 * @package core\components\applicationWeb
 */
class component extends applicationConnectors\AApplication implements applicationConnectors\IApplication, componentsConnectors\IComponent
{

}