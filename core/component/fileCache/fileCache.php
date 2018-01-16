<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.6.2017
 * Time: 15:17
 */

namespace core\component\fileCache;

use core\core;


/**
 * Class fileCache
 *
 * @package core\component\fileCache
 */
class fileCache
{


	/**
	 * Проверяет на наличие дирректорию и создает ее в случае необходимости
	 * @param string $dir дирректория
	 * @param bool   $needAbsolute нужен абсолютный путь
	 *
	 * @return string путь
	 */
	public static function getDir(string $dir, $needAbsolute = true): string
	{
		$dirAbsolute    =   core::getDirFileCache() . $dir;
		if (!file_exists($dirAbsolute) && !mkdir($dirAbsolute, 0777, true) && !is_dir($dirAbsolute)) {
			die('Не могу создать дирректорию ' . $dir);
		}
		return $needAbsolute    ?   $dirAbsolute    :   $dir;
	}

	/**
	 * Проверяет на наличие дирректорию и создает ее в случае необходимости
	 * @param string $dir дирректория
	 */
	public static function checkDir(string $dir)
	{
		$dirAbsolute    =   core::getDirFileCache() . $dir;
		if (!file_exists($dirAbsolute) && !mkdir($dirAbsolute, 0777, true) && !is_dir($dirAbsolute)) {
			die('Не могу создать дирректорию ' . $dir);
		}
	}

}