<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 23.07.17
 * Time: 22:23
 */

namespace Core\_library;

/**
 * Class component
 *
 * @package core\library
 */
class component
{

	/**
	 * @param $name
	 *
	 * @return string
	 */
    public static function connect($name)
    {
        $vendor = __NAMESPACE__ . "\\vendor\\{$name}\component";
        if (class_exists($vendor)) {
            $vendor = new $vendor();
        }
		return $vendor;
    }
}