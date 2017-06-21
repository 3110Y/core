<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 16:06
 */

namespace core\component\templateEngine;

/**
 * Interface IEngine
 * @package core\components\templateEngine
 */
interface IEngine
{
    /**
     * Рендерит данные
     * @return string результат
     */
    public function run();
}