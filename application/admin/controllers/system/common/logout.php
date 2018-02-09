<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 6:28
 */

namespace application\controllers\system\common;

use \core\component\{
    application\AControllers,
    registry\registry
};


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
        /** @var \core\component\authentication\component $auth */
        $auth    =   registry::get('auth');
        $auth->get('authorization')->logout();
        self::redirect(self::$application['url']);
    }
}