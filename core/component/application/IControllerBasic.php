<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 14:15
 */

namespace core\component\application;

/**
 * Interface IControllerBasic
 * @package core\component\application
 */
interface IControllerBasic
{
    /**
     * Преинициализация
     */
    public function pre(): void;

    /**
     * Постинициализация
     */
    public function post(): void;

    /**
     * Преинициализация
     */
    public function preAjax(): void;

    /**
     * Постинициализация
     */
    public function postAjax(): void;
}