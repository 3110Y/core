<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:06
 */

namespace core\connectors\app;

/**
 * Interface IRouter
 * @package core\connectors\app
 */
interface IRouter
{
    /**
     * Запускает роутинг
     * @return router
     */
    public function run();

    /**
     * Запускает роутинг
     * @return router
     */
    public function render();
}