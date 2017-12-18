<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 6:28
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    application\handler\Web as applicationWeb,
    authorization as authorization
};


class logout extends applicationWeb\AControllers implements applicationWeb\IControllers
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
        authorization\component::logout();
        self::redirect(self::$application['url']);
    }
}