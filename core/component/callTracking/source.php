<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 13:19
 */


namespace core\component\callTracking;


use core\component\registry\registry;


/**
 * Class source
 * @package core\component\callTracking
 */
class source
{
    /**
     * @var int
     */
    private static $id     =   0;

    /**
     * @var string
     */
    private static $name  =   '';

    /**
     * @var int
     */
    private static $count_visit  =   0;

    /**
     * @param $name
     */
    public static function set($name): void
    {
        self::$name =  parse_url($name, PHP_URL_HOST);
        self::load();
    }

    /**
     * Загружает данные
     */
    private static function load(): void
    {
        /** @var \core\component\PDO\PDO $db */
        $db     =   registry::get('db');
        $where  =   [
            'name'  =>  self::$name
        ];
        $query  =   $db->select('callTracking_source', '*', $where);
        if ($query->rowCount() > 0) {
            $row   =    $query->fetch();
            self::$id           =   $row['id'];
            self::$count_visit  =   $row['count_visit'];
        } else {
            $value = [
                'name'  => self::$name
            ];
            $db->inset('callTracking_source', $value);
            self::$id   =   $db->getLastID();
            self::$count_visit  =   0;
        }
        self::$count_visit++;
        $where  =   [
            'id'  =>  self::$id
        ];
        $value = [
            'count_visit'  => self::$count_visit
        ];
        $db->update('callTracking_source', $value, $where);
    }

    /**
     * @return int
     */
    public static function getID(): int
    {
        return self::$id;
    }
}