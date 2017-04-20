<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:42
 */

namespace core\components\PDO;
use core\components\database\connectors as databaseConnectors;
/**
 * Class component
 * компонент PDO
 * @package core\components\PDO
 */
class component extends databaseConnectors\ADatabase implements databaseConnectors\IDatabase
{

}