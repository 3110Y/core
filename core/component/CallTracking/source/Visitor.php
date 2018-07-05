<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 16:45
 */

namespace core\component\CallTracking\source;


use core\component\PDO\PDO;
use core\component\registry\registry;

class Visitor
{
    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_visitor';

    /** @var RequestData данные запроса */
    private $requestData;

    /** @var array Данные посетителя */
    private $visitorData;

    /**
     * Добавляем посетителя в БД
     *
     * @return void
     */
    private function insert(): void
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $db->inset(self::$tableName, $this->visitorData);
        $this->visitorData['id'] = $db->getLastID();
    }

    /**
     * Получаем посетителя из БД
     *
     * @return void
     */
    private function load(): void
    {
        /** @var PDO $db */
        $db = registry::get('db');

        $visitor = $db->selectRow(self::$tableName,'id',$this->visitorData);
        if (false === $visitor){
            $this->insert();
        } else {
            $this->visitorData['id'] = $visitor['id'];
        }
    }

    /**
     * Регистрация посетителя
     *
     * @return bool
     */
    private function registry(): bool
    {
        $sessionID = $this->requestData->getSessionID();
        $this->visitorData = [
            'session_id' => $sessionID
        ];
        $this->load();
        return true;
    }

    public function __construct(RequestData $data)
    {
        $this->requestData = $data;
        $this->registry();
    }

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$tableName;
    }

    /**
     * Получаем ID посетителя
     *
     * @return int
     */
    public function getID(): int
    {
        return $this->visitorData['id'] ?? 0;
    }

    /**
     * Запрос на создание таблицы
     *
     * @return string
     */
    public static function getInstallQuery(): string
    {
        return '
            CREATE TABLE IF NOT EXISTS `' . self::$tableName . '` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `parent_id` int(11) NOT NULL DEFAULT \'0\',
              `session_id` varchar(255) NOT NULL COMMENT \'Идентификатор сессии\',
              `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
    }
}