<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 15.06.2018
 * Time: 17:11
 */

namespace core\component\CallTracking\source;


use core\component\PDO\PDO;
use core\component\registry\registry;

class Substitutions
{
    /** @var string Название таблицы в БД */
    private static $tableName = 'calltracking_substitution';

    /** @var Phones Объект телефонов */
    private $phone;

    /** @var Visitor Объект посещения */
    private $visitor;

    /** @var Phones Объект телефонов */
    private $numbers;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$tableName;
    }

    /**
     * Регистрация присвоения телефона посетителю
     *
     * @param int $number_id
     * @param int $availableCount
     */
    private function registry(int $number_id, int $availableCount): void
    {
        /** @var PDO $db */
        $db     =   registry::get('db');
        $data = [
            'phone_id'                  => $number_id,
            'visitor_id'                => $this->visitor->getID(),
            'available_phones_count'    =>  $availableCount,
        ];
        $db->inset(self::$tableName, $data);
    }

    private function visitorPhones(): array
    {
        /** @var PDO $db */
        $db     =   registry::get('db');
        $where = [
            'visitor_id' => $this->visitor->getID()
        ];
        $result = $db->selectRows(self::$tableName, 'phone_id',$where);
        return array_column($result,'phone_id');
    }

    public function getNumbers(): array
    {
        if (!$this->numbers) {
            if ($idList = $this->visitorPhones()) {
                $this->numbers = $this->phone->getUsed($idList);
            }
            else {
                $this->numbers = $this->phone->getLatest();
                foreach ((array) $this->numbers as $number) {
                    $this->registry($number['id'],$number['count']);
                }
                if (\count($this->numbers) === 0) {
                    $this->registry(0,0);
                }
            }
        }
        return $this->numbers;
    }

    public function __construct(RequestData $data, Visitor $visitor)
    {
        $this->phone = new Phones($data);

        $this->visitor = $visitor;
    }

    /**
     * Получаем виртуальный телефон по реальному
     *
     * @param string $realNumber
     * @return string
     */
    public function get(string $realNumber): string
    {
        $numbers = $this->getNumbers();
        foreach ($numbers as $number) {
            if ($realNumber === $number['real_phone_number']) {
                return $number['virtual_phone_number'];
            }
            if ($realNumber === $number['real_phone_text']) {
                return $number['virtual_phone_text'];
            }
        }
        return $realNumber;
    }

    /**
     * Нужна ли замена
     *
     * @param string $realNumber
     * @return bool
     */
    public function has(string $realNumber = ''): bool
    {
        if (empty($realNumber)) {
            return (bool) \count($this->getNumbers());
        }
        return $realNumber !== $this->get($realNumber);
    }

    /**
     * Получаем запись из БД
     *
     * @param array $where
     * @return array|null
     */
    private static function getRecord(array $where = []): ?array
    {
        /** @var PDO $db */
        $db = registry::get('db');
        $result = $db->selectRow(self::$tableName,'*',$where,'date_insert DESC');
        return $result ?: null;
    }

    /**
     * Получаем запись по ID телефона
     *
     * @param int $phoneID
     * @return array|null
     */
    public static function getRecordByPhoneID(int $phoneID): ?array
    {
        return static::getRecord(['phone_id' => $phoneID]);
    }

    /**
     * Получаем запись по ID телефона
     *
     * @param int $visitorID
     * @return array|null
     */
    public static function getRecordByVisitorID(int $visitorID): ?array
    {
        return static::getRecord(['visitor_id' => $visitorID]);
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
              `phone_id` int(11) NOT NULL DEFAULT \'0\'  COMMENT \'Идентификатор телефона\',
              `available_phones_count` tinyint(3) NOT NULL DEFAULT \'0\'  COMMENT \'Кол-во доступных телефонов в ротации\',
              `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
    }

}