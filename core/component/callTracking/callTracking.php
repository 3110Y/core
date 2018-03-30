<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 19:01
 */

namespace core\component\callTracking;


use core\component\registry\registry;

class callTracking
{

    public function __construct()
    {
        try {
            visit::set();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Подменяет номер
     * @param mixed $text
     * @return string
     */
    public function replace($text)   :   string
    {
        if (is_array($text)) {
            foreach ($text as $key  =>  $value) {
                $text[$key] = $this->replace($value);
            }
        } else {
            $array  =   [
                phone::getRedirection() => phone::getShown()
            ];
            $text   =   strtr($text, $array);
        }

        return $text;
    }

    public static function call($data): void
    {
        if (isset($data['id'])) {
            /** @var \core\component\PDO\PDO $db */
            $db = registry::get('db');
            $where = [
                'external_id' => $data['id']
            ];
            $query = $db->select('callTracking_call', 'id', $where);
            if ($query->rowCount() > 0) {
                self::updateCall($data);
            } else {
                self::newCall($data);
            }
        }
    }

    /**
     * @param array $data
     */
    private static function newCall(array $data = array())    :   void
    {
        phone::set($data['ext'],false, 'incoming');
        $where  =   [
            'phone_id'  =>   phone::getID()
        ];
        visit::get($where);
        $array  =   [
            'external_id'  =>   $data['id'],
            'phone'        =>   $data['int'],
            'phone_id'     =>   phone::getID(),
            'visit_id'     =>   visit::getID()
        ];
        (new call($array))->save();

    }

    /**
     * @param array $data
     */
    private static function updateCall(array $data = array())    :   void
    {
        $array  =   [
            'external_id'   =>   $data['id'],
            'record'        =>   $data['mp3link'],
            'durability'    =>   $data['duration']
        ];
        (new call($array))->save();
    }


    /**
     * @param array $data
     */
    public static function downloadRecord(array $data = array())    :   void
    {
        call::downloadRecord();
    }

    /**
     * Новый звонок
     * @param string $number
     */
    public function callOrder($number): void
    {
        (new callOrder($number))->save();
    }

    /**
     * Установка
     */
    public static function install(): void
    {
        $sql = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";

CREATE TABLE IF NOT EXISTS `callTracking_call` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `phone` varchar(255) NOT NULL COMMENT 'Звонивший номер',
  `phone_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Звонивший номер и Номер подмены ',
  `durability` int(11) NOT NULL COMMENT 'Длительность звонка',
  `record` text NOT NULL COMMENT 'Ссылка на файл записи',
  `visit_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Идентификатор визита звонка',
  `external_id` int(11) NOT NULL COMMENT 'внешнее ID',
  `record_is_downloaded` tinyint(1) NOT NULL DEFAULT '0',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `callTracking_call_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `number` varchar(255) NOT NULL COMMENT 'Номер телефона, заказавшего звонок',
  `visit_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Идентификатор визита звонка ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `callTracking_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `incoming` varchar(11) NOT NULL COMMENT 'Входящий номер',
  `shown` varchar(11) NOT NULL COMMENT 'Показываемый номер',
  `redirection` varchar(11) NOT NULL COMMENT 'Номер перенаправления',
  `count_call` int(11) NOT NULL DEFAULT '0' COMMENT 'количество вызовов',
  `basic` tinyint(1) NOT NULL DEFAULT '0',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `callTracking_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(255) NOT NULL,
  `count_visit` int(11) NOT NULL DEFAULT '0',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `callTracking_visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `session_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Идентификатор сессии',
  `phone_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Идентификатор телефона',
  `referer` varchar(255) NOT NULL DEFAULT '0' COMMENT 'сточник входа (referer)',
  `url` varchar(255) NOT NULL COMMENT 'Посещаемая страница (внутренний url)',
  `source_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Источник (идентификатор)',
  `utm_source` text NOT NULL,
  `utm_keyword` text NOT NULL,
  `utm_content` text NOT NULL,
  `utm_medium` text NOT NULL,
  `utm_campaign` text NOT NULL,
  `utm_term` text NOT NULL,
  `utm_fastlink` text NOT NULL,
  `previous_id` int(11) DEFAULT '0' COMMENT 'ID предыдущего',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";
    }
}