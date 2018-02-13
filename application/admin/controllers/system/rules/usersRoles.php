<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.9.2017
 * Time: 03:30
 */

namespace application\admin\controllers\system\rules;

use core\component\application\AControllers;


/**
 * Class usersRoles
 *
 * @package application\controllers
 */
class usersRoles extends AControllers
{
	/**
	 * @var mixed|int|false Колличество подуровней
	 */
	public static $countSubURL  =   0;

	/**
	 * Инициализация
	 */
	public function __construct()
	{
		self::redirect(self::$pageURL . '/user');
	}
}