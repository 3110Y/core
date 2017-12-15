<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 23.07.17
 * Time: 22:23
 */

namespace core\component\library;

/**
 * Class component
 *
 * @package core\component\library
 */
class component
{
    /**
     * @const float Версия
     */
    const VERSION   =   1.1;

	/**
	 * @var string
	 */
    private static $namespace = '';

	/**
	 * @param $name
	 *
	 * @return string
	 */
    public static function connect($name)
    {
		self::$namespace  =   __NAMESPACE__ . "\\vendor\\{$name}\component";
		return self::$namespace;
    }
}