<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 5:25
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    application\handler\Web as applicationWeb,
    authorization as authorization
};


class enter extends applicationWeb\AControllers implements applicationWeb\IControllers
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

        if (self::$isAjaxRequest ) {
            if (!isset($_POST['login'], $_POST['password'])) {
                self::$content = false;
            } else {
                $login          =   $_POST['login'];
                $password       =   $_POST['password'];
                /** @var \core\component\authentication\component $auth */
                $auth    =   self::get('auth');
                self::$content = $auth->get('authorization')->login($login, $password);

            }
        } else {
            if (isset($_COOKIE['uid'])) {
                self::redirect(self::$application['url']);
            }
            self::setCss(self::getTemplate('css/enter.css'));
            self::setJs(self::getTemplate('js/enter.js'));
        }
    }
}