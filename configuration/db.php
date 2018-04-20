<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.6.2017
 * Time: 13:55
 */

if (!class_exists(\Core\core::class)) {
    die();
}
return Array(
	'driver'            =>  'mysql',
	'host'              =>  '127.0.0.1',
	'port'              =>  '3306',
	'db'                =>  'core',
	'name'              =>  'user',
	'pass'              =>  'password',
	'character'         =>  'UTF8',
);