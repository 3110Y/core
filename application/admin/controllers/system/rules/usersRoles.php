<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.9.2017
 * Time: 03:30
 */

namespace application\admin\controllers\system\rules;

use Core\{
    _application\controller\AController,
    router\route
};



/**
 * Class usersRoles
 *
 * @package application\controllers
 */
class usersRoles extends AController
{
	/**
	 * @var mixed|int|false Колличество подуровней
	 */
	public static $countSubURL  =   0;

    /**
     * Инициализация
     * @param route $route
     */
	public function __construct(route $route)
	{
		self::redirect(self::$pageURL . '/user');
	}
}