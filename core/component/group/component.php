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

        if (isset($_COOKIE['gid']) && is_int($_COOKIE['gid'])) {
            return Array($_COOKIE['gid']);
        }
        if (isset($_COOKIE['gid'])) {
            return explode(',', $_COOKIE['gid']);
        }
        return Array(0);
    }
}