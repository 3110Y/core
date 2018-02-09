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
\core\core::setDirConfig('configuration');
\core\core::setDirFileCache('filecache');
\core\core::getInstance()->register();
\core\core::getInstance()->addNamespace('core', 'core');
$config = \core\core::getConfig('db.common');
/** @var \core\component\PDO\PDO $db */
$db =   \core\component\PDO\PDO::getInstance($config);
$structure  =   $db->selectRows('core_application','*', Array( 'status' => '1'), '`priority` ASC');
if (isset($_SERVER['SHELL'], $argv)) {
    $URL    =   $argv;
    $URL[0] = '/';
} else {
    $URL    =   explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
$result = (new \core\router($structure, $URL))->run();
/** @var int Время Конца */
$timeEnd = microtime(true);
/** @var int Время Разница*/
$timeDiff = $timeEnd - $timeStart;
echo strtr($result, Array(
    '{time_DIFF}' => $timeDiff,
));
