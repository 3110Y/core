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

        /** @var  $db \core\components\PDO\component */
        $db =   self::getRouter()->get('db');

        $scheme = Array(
            Array(
                'gf-name'   => 'div',
                'id'        =>  'test',
                'class'     =>  'test_class1 test_class2',
                'gf-value' => Array(
                     Array(
                         'gf-name'      => 'p',
                         'id'           =>  'test',
                         'class'        =>  Array(
                             'test_class1',
                             'test_class2'
                         ),
                         'gf-value'     => 'Привет'
                     ),
                    Array(
                         'gf-name'      => 'hr',
                         'id'           =>  'test',
                         'class'        =>  Array(
                             'test_class1',
                             'test_class2'
                         ),
                     ),
                    Array(
                        'gf-name'   =>  'form',
                        'id'        =>  'test',
                        'method'    =>  'post',
                        'action'    =>  '',
                        'class'     =>  'test_class1 test_class2',
                        'gf-value' => Array(
                            Array(
                                'gf-name'      => 'input',
                              //  'gf-handler'    =>  'input',
                                'gf-field'      =>  'input',
                                'name'          =>  'field1',
                                'id'            =>  'test',
                                'class'         =>  'test_class1 test_class2',
                            ),
                            Array(
                                'gf-name'      => 'input',
                                // 'gf-handler'                    =>  'input',
                                'gf-field'                      =>  'input',
                                'gf-function-before-insert'     =>  function() {
                                    phpinfo();
                                },
                                'name'                          =>  'field1',
                                'id'                            =>  'test',
                                'class'                         =>  'test_class1 test_class2',
                            ),
                        ),
                    ),
                ),
            ),
        );
        $HTML =   self::getRouter()->get('GF')::construct($scheme);

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
            'TEXT'        =>  $HTML . ($test   ?   "Ключ сессии test {$testValue}" :   'сессии test нет. Устанавливаем'),
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
    }

}
