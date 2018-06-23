<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 16:45
 */

namespace core\component\CallTracking\source;


use core\component\registry\registry;

class Source
{
    /** @var string Название таблицы в БД */
    private static $tableName = 'calltracking_source';

    /**
     * @param string $sourceName
     * @return int
     */
    public static function getID(string $sourceName): int
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        $data = [
            'name'  => $sourceName
        ];
        $source = $db->selectRow(self::$tableName, 'id',$data);

        $id = $source['id'] ?? 0;

        if (0 === $id) {
            $db->inset(self::$tableName,$data);
            $id = $db->getLastID();
        }

        return $id;
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        $where = [
            [
                'field' => 'name',
                'cond' => '!=',
                'value' => '',
            ]
        ];
        return $db->selectRows(self::$tableName, '*', $where);
    }

    /**
     * @param int $sourceID
     * @return string
     */
    public static function getByID(int $sourceID): string
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        $data = [
            'id'  => $sourceID
        ];
        $source = $db->selectRow(self::$tableName, 'name',$data);

        return $source['name'] ?? '';
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
              `name` varchar(255) NOT NULL,
              `date_update` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',
              `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
    }
}