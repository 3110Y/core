<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:06
 */

namespace core\component\application\handler\Web;

/**
 * Interface IRouter
 * @package core\components\application\handler\Web
 */
interface IRouter
{
    /**
     * Запускает роутинг
     * @return object
     */
    public function run();

    /**
     * Отдает данные
     * @return string
     */
    public function render();
}