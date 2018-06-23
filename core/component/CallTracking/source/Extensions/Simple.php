<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 11:10
 */

namespace core\component\CallTracking\source\Extensions;


use core\component\CallTracking\source\AExtension;

class Simple extends AExtension
{
    /** Название действия */
    public const actionName = 'Действие не задано';

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string
    {
        return '';
    }
}