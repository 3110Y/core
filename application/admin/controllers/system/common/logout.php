<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 6:28
 */

namespace application\admin\controllers\system\common;

use Core\{
    _application\controller\AController,
    _registry\registry,
    router\route
};

/**
 * Class logout
 * @package application\admin\controllers\system\common
 */
class logout extends AController
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
        /** @var \Core\_authentication\component $auth */
        $auth    =   registry::get('auth');
        $auth->get('authorization')->logout();
        self::redirect(self::$applicationRoute->getURL());
    }
}