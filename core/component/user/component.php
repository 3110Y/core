<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 21:24
 */

namespace core\component\user;


/**
 * Class component
 * @package core\component\user
 */
class component
{
    /**
     * Отдает ID user
     * @return int ID
     */
    public static function get(): int
    {
        return isset($_COOKIE['uid']) ? $_COOKIE['uid'] : 0;
    }
}