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
     * @return int ID
     */
    public static function get(): int
    {
        return isset($_COOKIE['gid']) ? $_COOKIE['gid'] : 0;
    }
}