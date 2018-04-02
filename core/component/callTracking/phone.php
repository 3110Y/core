<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 14:45
 */

namespace core\component\callTracking;


use core\component\registry\registry;


/**
 * Class phone
 * @package core\component\callTracking
 */
class phone
{

    private static $id  =   0;

    private static $incoming   =   '';

    private static $shown   =   '';

    private static $redirection   =   '';

    /**
     * @param int $id
     * @param bool $needSubstitution
     * @param string $key
     */
    public static function set($id  =   0,  bool $needSubstitution = false, $key =  'id'): void
    {
        /** @var \core\component\PDO\PDO $db */
        $db =   registry::get('db');
        if ($needSubstitution) {
            $where  =   [
                [
                    'f' =>  'basic',
                    'c' =>  '!=',
                    'v' =>  1
                ]
            ];
            $order  =   '`count_call` ASC';
        } else {
            $where =    [
                'basic'  => 1
            ];
            $order  =   null;
        }
        if ($id != 0) {
            $where =    [
                $key  => $id
            ];
            $order  =   null;
        }
        $row                =   $db->selectRow('callTracking_phone', '*', $where, $order, '0, 1');
        if (isset($row['id'], $row['incoming'], $row['shown'], $row['redirection'])) {
            self::$id = $row['id'];
            self::$incoming = $row['incoming'];
            self::$shown = $row['shown'];
            self::$redirection = $row['redirection'];
            if ($needSubstitution) {
                $where = [
                    'id' => self::$id
                ];
                $value = [
                    'count_call' => $row['id'] + 1
                ];
                $db->update('callTracking_phone', $value, $where);
            }
        }
    }

    /**
     * @return string
     */
    public static function getIncoming()
    {
        return self::$incoming;
    }

    /**
     * @return string
     */
    public static function getShown()
    {
        return self::$shown;
    }

    /**
     * @return string
     */
    public static function getRedirection()
    {
        return self::$redirection;
    }


    public static function getID()
    {
        return self::$id;
    }

}