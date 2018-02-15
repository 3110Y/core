<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 15.02.18
 * Time: 19:42
 */

namespace application\admin\model;

use core\{
    application\AClass
};



class menu extends AClass
{
    public static function get($where)  :   array
    {

    }

    public static function getList($where)  :   array
    {
        $db = registry::get('db');
        /** @var \core\PDO\PDO $db */
        $query  =   $db->select('admin_page', '*', $where, 'order_in_menu');
    }

    public static function getMenu($where)  :   array
    {

    }
}