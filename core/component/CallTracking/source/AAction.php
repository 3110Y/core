<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 14:47
 */

namespace core\component\CallTracking\source;


use core\component\registry\registry;

abstract class AAction
{
    /** @var RequestData данные запроса */
    protected $requestData;

    /** @var string Название таблицы в БД */
    protected static $tableName = '';

    /** @var array Список полей данных */
    protected static $actionFields = [];

    /** @var array Данные действия */
    protected $data = [];

    /**
     * AAction constructor.
     * @param RequestData $requestData
     */
    public function __construct(RequestData $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * Задаём данные действия
     *
     * @param array $data
     */
    public function setActionData(array $data): void
    {
        foreach (static::$actionFields as $actionField) {
            if (isset($data[$actionField])) {
                $this->data[$actionField] = $data[$actionField];
            }
        }
   }

    /**
     * @return int
     */
    protected function insert(): int
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   registry::get('db');
        $db->inset(static::$tableName, $this->data);
        $this->data['id'] = $db->getLastID();
        return (bool) $this->data['id'];
    }

    /**
     * @param int|null $id
     * @return bool
     */
    protected function update(?int $id = null): bool
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   registry::get('db');
        $result = $db->update(static::$tableName, $this->data, ['id' => $id ?? $this->data['id']]);
        return (bool) $result->rowCount();
    }
}