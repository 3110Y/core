<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 21:33
 */

namespace core\component\group;

/**
 * Class component
 * @package core\component\group
 */
class component
{
    /**
     * Отдает ID group
     * @return mixed|string|array|int ID
     */
    public static function get()
    {
        return isset($_COOKIE['gid']) ? $_COOKIE['gid'] : Array(0);
    }
}