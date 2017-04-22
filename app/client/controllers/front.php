<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace app\client\controllers;

use core\components\applicationWeb\connectors;
use app\client\classes;
use core\components\database\connectors\ADatabase;


/**
 * Class front
 * Контроллер главной страницы
 * @package app\controllers
 */
class front extends connectors\AControllers implements connectors\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    protected static $countSubURL  =   0;

    /**
     * Инициализация
     */
    public function init()
    {

        $test       =   classes\session::getInstance()->exist('test');
        $testValue  =   '';
        if (!$test) {
            classes\session::getInstance()->set('test','test');
        } else {
            $testValue  =   classes\session::getInstance()->get('test');
        }
        $i =0;
var_dump(($i % 2) == false);
        /** @var  $db \core\components\PDO\component */
        $db =   self::getRouter()->get('db');
        $table = Array(
            'test1',
            'test2',
            'test3'=>'t3',
            Array(
                'table' =>  'test4',
                'as'    =>  'T4',
                'JOIN'  =>  'LEFT',
                'ON'    =>  '`USER_ID` = `id`'
            ),
            Array(
                'table' =>  'test5',
                'as'    =>  'T5',
                'JOIN'  =>  'LEFT',
                'ON'    =>  array(
                    'USER_ID'=>'`id`'

                ),
            ),
        );
        $where = Array(
            'id1'   => 1,
            'id2'   => 2,
            'AND',
            Array(
                'id3'   => 3,
                'OR',
                'id4'   => 4,
                'OR',
                'id5'   => Array(
                    'c' => '><',
                    'v' => 6
                ),
                Array(
                    'f' => 'id6',
                    'c' => '><',
                    'v' => 6
                ),
            ),
            'id7' => 'NULL',
            'id8' => '!NULL',
            'AND',
            'id9' => '`id0`'
        );
        /** @var \core\components\PDO\component $db */
        $sql =  $db->selectGenerator($table, '*', $where);

        $this->content  = Array(
            'NAME'        =>  'Это Фронтальный контроллер',
            'FOR'         =>  Array(
                Array(
                    'URL'     => 'TEST_1',
                    'NAME'    => 'TEST_1'
                ),
                Array(
                    'URL'     => 'TEST_2',
                    'NAME'    => 'TEST_2'
                ),
            ),
            'TEXT'        =>  $test   ?   "Ключ сессии test {$testValue}" . "<pre>" . print_r($sql,true) . "</pre>" :   'сессии test нет. Устанавливаем<br>',
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
    }

}
