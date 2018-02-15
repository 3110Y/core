<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 3.5.2017
 * Time: 14:15
 */

namespace core\application;

/**
 * Interface IControllerBasic
 * @package core\application
 */
interface IControllerBasic
{
    /**
     * Преинициализация
     */
    public static function pre();

    /**
     * Постинициализация
     */
    public static function post();

    /**
     * Преинициализация
     */
    public static function preAjax();

    /**
     * Постинициализация
     */
    public static function postAjax();
}