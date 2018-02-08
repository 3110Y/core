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
    public function pre();

    /**
     * Постинициализация
     */
    public function post();

    /**
     * Преинициализация
     */
    public function preAjax();

    /**
     * Постинициализация
     */
    public function postAjax();
}