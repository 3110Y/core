<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.4.2017
 * Time: 16:06
 */

namespace core\components\view\connectors;

/**
 * Interface IView
 * Базовый Коннектор шаблонизатора
 * @package core\components\view\connectors
 */
interface IView
{
    /**
     * Рендерит данные
     * @param string $template шаблон
     * @param array $data Данные
     * @return string результат
     */
    public static function run($template, array $data = Array());
}