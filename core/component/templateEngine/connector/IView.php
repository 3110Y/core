<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 16:06
 */

namespace core\component\templateEngine\connector;

/**
 * Interface IConnector
 * @package core\components\view\connectors
 */
interface IConnector
{
    /**
     * Рендерит данные
     * @return string результат
     */
    public function run();
}