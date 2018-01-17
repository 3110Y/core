<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 6:28
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    application as application,
    authorization as authorization
};


class logout extends application\AControllers implements application\IControllers
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
        /** @var \core\component\authentication\component $auth */
        $auth    =   self::get('auth');
        $auth->get('authorization')->logout();
        self::redirect(self::$application['url']);
    }
}