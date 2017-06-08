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
\core\core::getInstance()->addNamespace('core', 'core');
$config = Array(
    'driver'            =>  'mysql',
    'host'              =>  '127.0.0.1',
    'port'              =>  '3306',
    'db'                =>  'core',
    'name'              =>  'core',
    'pass'              =>  'corecore',
    'character'         =>  'UTF8',
);

/** @var \core\component\database\driver\PDO\component $db */
$db =   \core\component\database\driver\PDO\component::getInstance($config);
$structure  =   $db->selectRows('core_application','*', Array( 'status' => '1'), '`priority` ASC');
$result = (new \core\router($structure))->run();
/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
));
