<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 10:49
 */

namespace core\component\CallTracking\source;


interface IExtension
{
    /**
     * IExtension constructor.
     * @param RequestData $requestData
     */
    public function __construct(RequestData $requestData);

    /**
     * Функции API
     *
     * @param $functionName
     * @return mixed
     */
    public function api($functionName);

    /**
     * Добавление действия пользователя
     *
     * @param Action $action
     * @return bool
     */
    public function registry(Action $action): bool;

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string;

    /**
     * Задаём данные действия
     *
     * @param array $data
     */
    public function setActionData(array $data): void;

    /**
     * Получение дополнительных данных для данного запроса по id действия
     *
     * @param Action $action
     * @return mixed
     */
    public function getExtensionData(Action $action);
}