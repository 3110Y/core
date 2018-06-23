<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 15:53
 */

namespace core\component\CallTracking\source;


interface ICallTracking
{
    /**
     * Подменяет дефолтные номера телефонов на номера из пула
     *
     * @param array|string $data
     * @return mixed
     */
    public function replace($data);

    /**
     * Получаем замену реального номера на виртуальный
     *
     * @param string $realNumber
     * @return string
     */
    public function getSubstitution(string $realNumber): string;

    /**
     * Получаем замену реального номера на виртуальный
     *
     * @param string $realNumber
     * @return bool
     */
    public function hasSubstitution(string $realNumber = ''): bool;

    /**
     * Регистрируем действие пользователя
     *
     * @param string $actionKey - название действия,
     * @param array $data - [ дополнительные значения ]
     * @return bool
     */
    public static function action(string $actionKey, array $data = []): bool;

    /**
     * Импорт структуры таблиц в БД
     *
     * @return bool
     */
    public static function install(): bool;
}