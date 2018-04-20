<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.03.18
 * Time: 19:36
 */

namespace Core\_lang;


use Core\_registry\registry;

class lang
{
    public static function replace(string $html, string $table = 'lang_word') : string
    {
        /** @var \Core\PDO\PDO $db */
        $db =   registry::get('db');
        $rows   =   $db->selectRows($table,'`name`, `translate`');
        $array  =    [];
        foreach ($rows as $row) {
            $array[$row['name']]    =   $row['translate'];
        }
        return strtr($html, $array);
    }
}