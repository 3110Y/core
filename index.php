<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 11:12
 */

error_reporting (E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
/** @var int Время Старта */
$timeStart  = microtime(true);
include 'core' . DIRECTORY_SEPARATOR . 'core.php';
\core\core::setDR(__DIR__);
\core\core::getInstance()->register();
\core\core::getInstance()->addNamespace('core', '/core');
$structure  =   Array(
    Array(
        'name'      => 'Клиент',
        'url'       => '/admin',
        'path'      => 'admin',
        'priority'  => 10,
        'theme'     => 'basic',
        'handler'   => 'Web',
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
