<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 14:47
 */

namespace core\component\CallTracking\source;


use core\component\PDO\PDO;
use core\component\registry\registry;

abstract class AExtension extends AAction implements IExtension
{
    /** @var string список столбцов в БД */
    protected static $colsInstallQueryData;

    /**
     * Добавление действия пользователя
     *
     * @param Action $action
     * @return bool
     */
    public function registry(Action $action): bool
    {
        $visitor = new Visitor($this->requestData);
        $action->setActionData([
            'visitor_id'    => $visitor->getID(),
        ]);
        $action->registry();
        $this->setActionData(['action_id' => $action->getID()]);
        if (static::$tableName) {
            return (bool) $this->insert();
        }
        return true;
    }

    /**
     * Функции API
     *
     * @param $functionName
     * @return mixed
     */
    public function api($functionName)
    {
        if (\is_callable([$this,$functionName])) {
            return $this->$functionName();
        }
        return null;
    }

    /**
     * Получение дополнительных данных для данного запроса по id действия
     *
     * @param Action $action
     * @return array
     */
    public function getExtensionData(Action $action): array
    {
        /** @var PDO $db */
        $db     =   registry::get('db');
        if (!static::$tableName) {
            return [];
        }
        $where = [
            'action_id' => $action->getID()
        ];
        return $db->selectRow(static::$tableName, '*', $where) ?: [];
    }

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string
    {
        return '
            CREATE TABLE IF NOT EXISTS `' . static::$tableName.'` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `parent_id` int(11) NOT NULL DEFAULT \'0\',
              `action_id` int(11) NOT NULL DEFAULT \'0\' COMMENT \'Идентификатор действия\',
              ' . (static::$colsInstallQueryData ? rtrim(rtrim(static::$colsInstallQueryData),',') . ',' : ''). '
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
    }

    /**
     * Получение данных о посещении.
     *
     * @param Action $action
     * @param array|null $visitData - массив данных о посещении
     * @return array|null
     */
    public function getVisitData(Action $action, ?array $visitData = null): ?array
    {
        return $visitData;
    }
}