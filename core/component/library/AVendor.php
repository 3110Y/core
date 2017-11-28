<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.11.2017
 * Time: 21:22
 */

namespace core\component\library;


abstract class AVendor
{
	/**
	 * @var array
	 */
	private static $js = Array();

	/**
	 * @var array
	 */
	private static $css = Array();

	/**
	 * @var array
	 */
	private static $template = Array();

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function getJS($name)
	{
		return self::$js[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function getCss($name)
	{
		return self::$css[$name];
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public static function getTemplate($name)
	{
		return self::$template[$name];
	}
}