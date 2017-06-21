<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 14:15
 */

namespace core\component\application\handler\Web;

/**
 * Interface IControllerBasic
 * @package core\component\application\handler\Web
 */
interface IControllerBasic
{
    /**
     * Преинициализация
     */
    public function preInit();

    /**
     * Постинициализация
     */
    public function postInit();
}