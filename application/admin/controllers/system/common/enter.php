<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 5:25
 */

namespace application\admin\controllers\system\common;

use \core\component\{
    application as application,
    registry\registry,
    resources\resources
};


class enter extends application\AControllers implements application\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * @var string шаблон
     */
    public  $template = 'enter';

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
                $auth    =   registry::get('auth');
                self::$content = $auth->get('authorization')->login($login, $password);

            }
        } else {
            if (isset($_COOKIE['uid'])) {
                self::redirect(self::$application['url']);
            }
            resources::setCss(self::getTemplate('css/enter.css'));
            resources::setJs(self::getTemplate('js/enter.js'));
        }
    }
}