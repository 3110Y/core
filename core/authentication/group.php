<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:03
 */

namespace core\authentication;


class group extends AAuthentication
{

    /**
     * @return array
     */
    public static function get()
    {
        return isset($_COOKIE['gid']) ? explode(',', $_COOKIE['gid']) : Array();
    }
}