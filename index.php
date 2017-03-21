<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 11:12
 */
/**
 * need < PHP 5.6
 *
 */
error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
/** @var int Время Старта */
$timeStart  = microtime(true);

include 'core' . DIRECTORY_SEPARATOR . 'core.php';
$architecture = Array(
    'core'  =>  Array(
        '(core|router)',
        'connectors'    => Array(
            'app'   =>  '([\w]+)',
        ),
        'components'    => Array(
            'simpleView'  =>   '([\w]+)',
        ),
    ),
    'app'   =>  Array(
        '(router)',
        'classes'       => '([\w]+)',
        'controllers'   => '([\w]+)',
        'theme'         => false,
    ),
);
\Core\Core::init($architecture);
$router =   new \core\router();
$router =   $router->getAppRouter()->run();
$result =   $router->render();

/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
));
