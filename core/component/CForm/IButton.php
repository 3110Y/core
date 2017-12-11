<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 11.12.2017
 * Time: 11:55
 */

namespace core\component\CForm;

/**
 * Interface IButton
 * @package core\component\CForm
 */
interface IButton
{
    /**
     * Инициализация
     */
    public function init();

    /**
     * Инициализация
     */
    public function run();


    /**
     * @return string
     */
    public function getAnswer();

    /**
     * @return array
     */
    public function getButton();
}