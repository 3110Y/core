<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:02
 */

namespace core\authentication;


class user extends AAuthentication
{

    /**
     * @return int
     */
    public static function get()
    {
        return isset($_COOKIE['uid']) ? $_COOKIE['uid'] : 0;
    }

}