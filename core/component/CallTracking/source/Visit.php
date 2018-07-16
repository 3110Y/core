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

class Visit
{
    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_visit';

    /** @var RequestData данные запроса */
    private $requestData;

    /** @var Visitor посетитель */
    private $visitor;

    /** @var array данные посещения  */
    private $visitData;

    /**
     * Добавляем визит в БД
     *
     * @return bool
     */
    private function insert(): bool
    {
        /** @var PDO $db */
        $db = registry::get('db');

        $db->inset(self::$tableName, $this->visitData);
        $this->visitData['id'] = $db->getLastID();

        return true;
    }

    /**
     * Регистрация посещения
     *
     * @return bool
     */
    private function registry(): bool
    {
        $this->visitData = [
            'visitor_id'    =>  $this->visitor->getID(),
            'url'           =>  $this->requestData->getRequestURI(),
            'referer'       =>  $this->requestData->getReferer(),
            'utm_source'    =>  $this->requestData->getSource(),
            'source_id'     =>  $this->requestData->getSourceID(),
            'utm_term'      =>  $this->requestData->getTerm(),
            'utm_content'   =>  $this->requestData->getContent(),
            'utm_medium'    =>  $this->requestData->getMedium(),
            'utm_campaign'  =>  $this->requestData->getCampaign(),
            'utm_keyword'   =>  $this->requestData->get('utm_keyword'),
            'utm_fastlink'  =>  $this->requestData->get('utm_fastlink'),
        ];


        return $this->insert();
    }

    public function __construct(RequestData $data, Visitor $visitor)
    {
        $this->requestData = $data;
        $this->visitor = $visitor;
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
     * Получаем ID визита
     *
     * @return int
     */
    public function getID(): int
    {
        return $this->visitData['id'] ?? 0;
    }

    /**
     * @param array $actions
     * @return array
     */
    public static function getActionsInfo(array $actions): array
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $idList = array_column($actions,'visitor_id');
        $query = /** @lang text */
            '
          SELECT `visit`.* 
          FROM (
              SELECT 
                `visitor_id`,
                `url`,
                `referer`,
                `source_id`,
                `utm_source`,
                `utm_term`,
                `utm_content`,
                `utm_medium`,
                `utm_campaign`,
                `utm_keyword`,
                `utm_fastlink`
              FROM `'. self::$tableName .'` `visit`
              WHERE FIND_IN_SET(`visitor_id`,"' . implode(',',$idList) . '") 
              ORDER BY `date_insert` ASC
          ) `visit`
          GROUP BY  `visitor_id`';
        $result = $db->query($query);
        return $result ? $result->fetchAll() : [];
    }

    /**
     * @return null|array
     */
    public static function getLastWithoutSubstitution(): ?array
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $query = '
            SELECT 
              `visit`.* 
            FROM 
              `' . self::$tableName . '` `visit`
                LEFT JOIN `' . Substitutions::getTableName() . '` `substitution`
                  ON (`visit`.`visitor_id` = `substitution`.`visitor_id`) 
            WHERE 
                `substitution`.`id` IS NULL 
            ORDER BY 
                `visit`.`date_insert` DESC 
            LIMIT 
                1';

        $result = $db->query($query);
        return $result ? $result->fetch() : null;
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
              `visitor_id` int(11) NOT NULL DEFAULT \'0\' COMMENT \'Идентификатор посетителя\',
              `referer` varchar(255) NOT NULL COMMENT \'Источник входа (referer)\',
              `url` varchar(255) NOT NULL COMMENT \'Посещаемая страница (внутренний url)\',
              `source_id` int(11) NOT NULL DEFAULT \'0\' COMMENT \'Идентификатор источника\',
              `utm_source` text,
              `utm_content` text,
              `utm_medium` text,
              `utm_campaign` text,
              `utm_term` text,
              `utm_keyword` text,
              `utm_fastlink` text,
              `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
    }
}