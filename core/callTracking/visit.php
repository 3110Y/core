<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 13:36
 */

namespace core\callTracking;


use core\registry\registry;

/**
 * Class visit
 * @package core\callTracking
 */
class visit
{
    /**
     * @var int
     */
    private static $id =   0;

    /**
     * @var int
     */
    private static $session_id  =   0;

    /**
     * @var int
     */
    private static $phone_id    =   0;

    /**
     * @var string
     */
    private static $referer     =   '';

    /**
     * @var int
     */
    private static $url         =   0;

    /**
     * @var int
     */
    private static $source_id   =   0;

    /**
     * @var string
     */
    private static $utm_source   =   '';

    /**
     * @var string
     */
    private static $utm_keyword   =   '';

    /**
     * @var string
     */
    private static $utm_content   =   '';

    /**
     * @var string
     */
    private static $utm_medium   =   '';

    /**
     * @var string
     */
    private static $utm_campaign   =   '';

    /**
     * @var string
     */
    private static $utm_term   =   '';

    /**
     * @var string
     */
    private static $utm_fastlink   =   '';

    /**
     * @var int
     */
    private static $previous_id        =   0;

    public static function get(array $where = Array())
    {
        /** @var \core\PDO\PDO $db */
        $db     =   registry::get('db');
        $row    =   $db->selectRow('callTracking_visit','*', $where);
        self::$id               =   $row['id'];
        self::$session_id       =   $row['session_id'];
        self::$referer          =   $row['referer'];
        self::$url              =   $row['url'];
        self::$phone_id         =   $row['phone_id '];
        self::$source_id        =   $row['source_id'];
        self::$utm_source       =   $row['utm_source'];
        self::$utm_keyword      =   $row['utm_keyword'];
        self::$utm_content      =   $row['utm_content'];
        self::$utm_medium       =   $row['utm_medium'];
        self::$utm_campaign     =   $row['utm_campaign'];
        self::$utm_term         =   $row['utm_term'];
        self::$utm_fastlink     =   $row['utm_fastlink'];
        self::$previous_id      =   $row['previous_id'];
    }

    /**
     * @throws \Exception
     */
    public static function set(): void
    {
        $random             =   strtr(uniqid(random_int(0, 999), true), ['.' => random_int(0, 9)]);
        if (!isset($_COOKIE['callTracking_session_id'])) {
            self::$session_id   =    (int)$random;
            setcookie('callTracking_session_id', self::$session_id, time() + 2592000, '/');
        } else {
            self::$session_id   = (int)$_COOKIE['callTracking_session_id'];
        }
        phone::set(self::loadLastPhoneID(), isset($_GET['utm_source']));
        source::set($_SERVER['REQUEST_URI']);
        self::$referer          =   $_SERVER['HTTP_REFERER']    ??  '';
        self::$url              =   $_SERVER['REQUEST_URI'];
        self::$phone_id         =   phone::getID();
        self::$source_id        =   source::getID();
        self::$utm_source       =   $_GET['utm_source']     ??  '';
        self::$utm_keyword      =   $_GET['utm_keyword']    ??  '';
        self::$utm_content      =   $_GET['utm_content']    ??  '';
        self::$utm_medium       =   $_GET['utm_medium']     ??  '';
        self::$utm_campaign     =   $_GET['utm_campaign']   ??  '';
        self::$utm_term         =   $_GET['utm_term']       ??  '';
        self::$utm_fastlink     =   $_GET['utm_fastlink']   ??  '';
    }

    /**
     * @return int
     */
    private static function loadLastPhoneID(): int
    {
        /** @var \core\PDO\PDO $db */
        $db =   registry::get('db');
        $where  =   [
            [
                'f' =>  'utm_source',
                'c' =>  '!=',
                'v' =>  ''
            ],
            'UNIX_TIMESTAMP(`date_update`) > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 DAY))',
            'session_id'  =>  self::$session_id
        ];
        $row    =   $db->selectRow(
            'callTracking_visit',
            'id, phone_id',
            $where,
            'date_update DESC',
            '0, 1'
        );
        if (isset($row['id'], $row['phone_id'])) {
            self::$previous_id  =   $row['id'];
            return $row['phone_id'];
        }
        return 0;
    }

    /**
     * Сохранение
     */
    public static function save(): void
    {
        /** @var \core\PDO\PDO $db */
        $db =   registry::get('db');
        $value  =   [
            'session_id'    =>  self::$session_id,
            'referer'       =>  self::$referer,
            'url'           =>  self::$url,
            'phone_id'      =>  self::$phone_id,
            'source_id'     =>  self::$source_id,
            'utm_source'    =>  self::$utm_source,
            'utm_keyword'   =>  self::$utm_keyword,
            'utm_content'   =>  self::$utm_content,
            'utm_medium'    =>  self::$utm_medium,
            'utm_campaign'  =>  self::$utm_campaign,
            'utm_term'      =>  self::$utm_term,
            'utm_fastlink'  =>  self::$utm_fastlink,
            'previous_id'   =>  self::$previous_id,
            'date_insert'   =>  date('Y-m-d H:i:s')
        ];
        $db->inset('callTracking_visit', $value);
        self::$id   =   $db->getLastID();
    }

    /**
     * @return int
     */
    public static function getID(): int
    {
        return self::$id;
    }
}