<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 6:28
 */

namespace application\admin\controllers\system\common;

use \core\{
    application\AControllers,
    registry\registry
};

/**
 * Class logout
 * @package application\admin\controllers\system\common
 */
class logout extends AControllers
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
        /** @var \core\authentication\component $auth */
        $auth    =   registry::get('auth');
        $auth->get('authorization')->logout();
        self::redirect(self::$application['url']);
    }
}