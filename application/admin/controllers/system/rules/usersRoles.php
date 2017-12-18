<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.9.2017
 * Time: 03:30
 */

namespace application\admin\controllers\system\rules;

use core\component\application\handler\Web as applicationWeb;


/**
 * Class usersRoles
 *
 * @package application\admin\controllers
 */
class usersRoles extends applicationWeb\AControllers implements applicationWeb\IControllers
{
	/**
	 * @var mixed|int|false Колличество подуровней
	 */
	public static $countSubURL  =   0;

	/**
	 * Инициализация
	 */
	public function init()
	{
		self::redirect(self::$pageURL . '/user');
	}
}