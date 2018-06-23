<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 13.06.2018
 * Time: 16:50
 */

namespace core\component\CallTracking\source;


use core\component\registry\registry;

class Phones
{
    /** @var string Название таблицы в БД */
    protected static $tableName = 'calltracking_phone';

    /** @var RequestData данные запроса */
    private $requestData;

    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return self::$tableName;
    }

    private function requestCondition(string $pattern, string $requestParam): bool
    {
        $pattern = '/^' . preg_quote($pattern, '/') . '$/is';

        $starPosition = 0;
        while (false !== $starPosition = strpos($pattern, '*', ++$starPosition)) {
            $slashesPosition = $starPosition;
            /** Он и должен быть пустым =)) */
            /** @noinspection LoopWhichDoesNotLoopInspection */
            /** @noinspection MissingOrEmptyGroupStatementInspection */
            /** @noinspection PhpStatementHasEmptyBodyInspection */
            while ($slashesPosition >= 0 && '\\' === $pattern[--$slashesPosition]) {}
            if (($starPosition - $slashesPosition - 1) % 4 === 1) {
                $pattern[$starPosition-1] = '.';
            }
        }
        return (bool) preg_match($pattern,$requestParam);
    }

    public function getLatest(): array
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        $where = [
            'status'    => 1,
        ];
        $order = '`date_update` ASC';
        $phones = $db->selectRows(self::$tableName, '*', $where, $order);
        $realPhones = [];

        foreach ($phones as $index => $phone) {
            $available = !\in_array($phone['real_phone_number'], $realPhones, true);
            $available &= $this->requestCondition($phone['referer'],        $this->requestData->getReferer());
            $available &= $this->requestCondition($phone['url'],            $this->requestData->getURLPath());
            $available &= $this->requestCondition($phone['utm_source'],     $this->requestData->getSource());
            $available &= $this->requestCondition($phone['utm_content'],    $this->requestData->getContent());
            $available &= $this->requestCondition($phone['utm_medium'],     $this->requestData->getMedium());
            $available &= $this->requestCondition($phone['utm_campaign'],   $this->requestData->getCampaign());
            $available &= $this->requestCondition($phone['utm_term'],       $this->requestData->getTerm());
            $available &= $this->requestCondition($phone['utm_keyword'],    $this->requestData->get('utm_keyword') ?? '');
            $available &= $this->requestCondition($phone['utm_fastlink'],   $this->requestData->get('utm_fastlink') ?? '');
            if ($available) {
                $realPhones[] = $phone['real_phone_number'];
            } else {
                unset($phones[$index]);
            }
        }
        $where = [
            'FIND_IN_SET(`id`,"' . implode(',', array_column($phones,'id')) . '")'
        ];
        $fields = [
            'date_update' => date('Y-m-d H:i:s')
        ];
        $db->update(self::$tableName, $fields, $where);
        return $phones;
    }

    public function getUsed(array $idList): array
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        $where = [
            'status'    => 1,
            'FIND_IN_SET(`id`,"' . implode(',', $idList) . '")'
        ];
        $fields = '`real_phone_number`,`real_phone_text`,`virtual_phone_number`,`virtual_phone_text`';
        $phones = $db->selectRows(self::$tableName, $fields, $where);

        $fields = [
            'date_update' => date('Y-m-d H:i:s')
        ];
        $db->update(self::$tableName, $fields, $where);
        return $phones;
    }

    /**
     * Получаем ID телефона по его значению
     *
     * @param string|null $phone
     * @return int|null
     */
    public function getVirtualID(?string $phone = null): ?int
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db = registry::get('db');
        if (null === $phone) {
            $phone = $this->requestData->getVirtualPhone();
        }
        if (null === $phone) {
            return null;
        }
        $where = [
            'virtual_phone_number' => $phone
        ];
        $result = $db->selectRow(self::$tableName,'id',$where);
        if (false === $result) {
            return null;
        }
        return (int) $result['id'];
    }

    public function __construct(RequestData $data)
    {
        $this->requestData = $data;
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
              `status` tinyint(1) NOT NULL DEFAULT \'3\',
              `real_phone_number` varchar(255) NOT NULL COMMENT \'Существующий номер телефона\',
              `real_phone_text` varchar(255) NOT NULL COMMENT \'Отображаемый существующий номер\',
              `virtual_phone_number` varchar(255) NOT NULL COMMENT \'Виртуальный номер телефона\',
              `virtual_phone_text` varchar(255) NOT NULL COMMENT \'Отображаемый виртуальный номер\',
              `referer` varchar(255) NOT NULL DEFAULT \'*\' COMMENT \'Источник перехода (referer)\',
              `url` varchar(255) NOT NULL DEFAULT \'*\' COMMENT \'Посещаемая страница (внутренний url)\',
              `utm_source` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_content` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_medium` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_campaign` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_term` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_keyword` varchar(255) NOT NULL DEFAULT \'*\',
              `utm_fastlink` varchar(255) NOT NULL DEFAULT \'*\',
              `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `date_insert` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
    }

    public static function regionalFormat(string $phone): string
    {
        $phone = trim($phone);
        return preg_replace('/^\+7/','8',$phone);
    }
    public static function internationalFormat(string $phone): string
    {
        $phone = trim($phone);
        return preg_replace('/^8/','+7',$phone);
    }
    public static function digitFormat(string $phone, $international = true): string
    {
        $phone = trim($phone);
        $phone = preg_replace('/\D/','',$phone);
        if ($international && \strlen($phone) === 11 && $phone[0] === '8') {
            $phone[0] = '7';
        }
        return $phone;
    }

    public static function humanFormat(string $phone): string
    {
        return self::format($phone, '8 (000) 000-00-00');
    }
    public static function shortFormat(string $phone): string
    {
        return self::format($phone, '000-00-00');
    }
    public static function format(string $phone, string $mask ): string
    {
        $phone = trim($phone);
        $phoneDigits = self::digitFormat($phone);
        if (\strlen($phoneDigits) === 10) {
            $phoneDigits = '7'.$phoneDigits;
        }
        $maskLength = \strlen($mask);
        $phoneLength = \strlen($phoneDigits);
        $phoneText = '';
        for ($i = $maskLength-1, $j = $phoneLength-1; $i >= 0 && $j >= 0; $i--) {
            if ($mask[$i] === '0') {
                $phoneText = $phoneDigits[$j] . $phoneText;
                $j--;
            } else {
                $phoneText = $mask[$i] . $phoneText;
            }
        }
        return $phoneText;
    }
}