<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 11:12
 */

//TODO: несколько приложений
//TODO: гибкость добавить
//TODO: относительность добавить
//TODO: очистить код


error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
/** @var int Время Старта */
$timeStart  = microtime(true);

include 'core' . DIRECTORY_SEPARATOR . 'core.php';
$architecture = Array(
    'core'  =>  Array(
        '(core|router)',
        'components'    => Array(
            'simpleView'        =>  Array(
                '([\w]+)',
                'connectors'=> '([\w]+)',
            ),
            'component'         =>  Array(
                '([\w]+)',
                'connectors'=> '([\w]+)',
            ),
            'applicationWeb'    =>  Array(
                '([\w]+)',
                'connectors'=> '([\w]+)',
            ),
            'application'       =>  Array(
                '([\w]+)',
                'connectors'=> '([\w]+)',
            ),
        ),
    ),
    'app'   =>  Array(
        'client'   =>  Array(
            '(router)',
            'classes'       => '([\w]+)',
            'controllers'   => '([\w]+)',
            'theme'         => false,
        ),
    ),
);
\Core\Core::init($architecture);
$structure  =   Array(
    Array(
        'name'      => 'Клиент',
        'url'       => '/',
        'path'      => 'client',
        'priority'  => 10,
        'theme'     => 'basic',
        'handler'   => \Core\Components\applicationWeb\Component::class,
    ),
);
$result = (new \core\router($structure))->run();

/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
));
