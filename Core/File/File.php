<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.6.2017
 * Time: 15:17
 */

namespace Core\File;

use  Core\Dir\Dir;


/**
 * Class fileCache
 *
 * @package core\File
 */
class File
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
		$dirAbsolute    =   Dir::getDirFileCache() . $dir;
		if (!file_exists($dirAbsolute) && !mkdir($dirAbsolute, 0777, true) && !is_dir($dirAbsolute)) {
			die('Не могу создать дирректорию ' . $dir);
		}
		return $needAbsolute    ?   $dirAbsolute    :   $dir;
	}

	/**
	 * Проверяет на наличие дирректорию и создает ее в случае необходимости
	 * @param string $dir дирректория
	 */
	public static function checkDir(string $dir): void
    {
		$dirAbsolute    =   Dir::getDirFileCache() . $dir;
		if (!file_exists($dirAbsolute) && !mkdir($dirAbsolute, 0777, true) && !is_dir($dirAbsolute)) {
			die('Не могу создать дирректорию ' . $dir);
		}
	}

}