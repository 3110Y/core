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
        $scheme = Array(
            Array(
                'tag'       => 'div',
                'id'        =>  'test',
                'class'     =>  'test_class1 test_class2',
                'children' => Array(
                     Array(
                         'tag'      => 'p',
                         'id'           =>  'test_id',
                         'class'        =>  Array(
                             'test_class1',
                             'test_class2'
                         ),
                         'children'     => 'Привет'
                     ),
                    Array(
                         'tag'      => 'hr',
                         'id'           =>  'test_id2',
                         'class'        =>  Array(
                             'test_class3',
                             'test_class4'
                         ),
                     ),
                    Array(
                        'tag'       =>  'form',
                        'id'        =>  'test',
                        'method'    =>  'post',
                        'action'    =>  '',
                        'class'     =>  'test_class1 test_class2',
                        'children' => Array(
                            'Привет',
                            Array(
                                'system'                        =>  Array(
                                    'handler'                    =>  'input',
                                    'field'                      =>  'input2',
                                ),
                                'id'            =>  'test',
                                'class'         =>  'test_class1 test_class2',
                            ),
                            Array(
                                'system'                        =>  Array(
                                    'handler'                    =>  'input',
                                    'field'                      =>  'input',
                                    'function-before-insert'     =>  function() {
                                        phpinfo();
                                    },
                                ),
                                'id'                            =>  'test',
                                'class'                         =>  'test_class1 test_class2',
                            ),
                        ),
                    ),
                ),
            ),
        );
        $data   = $_POST;
        $config =   Array(
            'connect'       => self::getRouter()->get('db'),
            'table'         => 'table',
            'id'            =>  isset($_GET['id'])  ?   $_GET['id'] :   0,
            'field'         =>  'id'
        );
        $GF     =   self::getRouter()->get('GF');
        /** @var \core\components\generatorForm\component $GF */
        $GF     =   new $GF();
        $GF->setScheme($scheme);
        $GF->setData($data);
        $GF->setConfig($config);
        $GF->run();
        $GF->save();
        $HTML   =   $GF->getHTML();
        $JS     =   $GF->getJS();
        $CSS    =   $GF->getCSS();


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
            'TEXT'        =>  ($test   ?   "Ключ сессии test {$testValue}" :   'сессии test нет. Устанавливаем'),
            'FORM'        =>  $HTML,
            'TITLE'       =>  self::$page['meta_title'],
            'KEYWORDS'    =>  self::$page['meta_keywords'],
            'DESCRIPTION' =>  self::$page['meta_description'],
        );
    }

}
