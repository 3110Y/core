<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 1:06
 */

namespace core\component\library;


interface IVendor
{

    /**
     * @param object $controller
     */
    public function setJS($controller);

    /**
     * @param object $controller
     */
    public function setCss($controller);
}