<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 5:25
 */

namespace application\admin\controllers\system\common;

use \Core\{
    application\controller\AController,
    registry\registry,
    resources\resources,
    router\route
};

/**
 * Class enter
 * @package application\admin\controllers\system\common
 */
class enter extends AController
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
     * @param route $route
     */
    public function __construct(route $route)
    {

        if (self::isAjaxRequest() ) {
            if (!isset($_POST['login'], $_POST['password'])) {
                self::$content = false;
            } else {
                $login          =   $_POST['login'];
                $password       =   $_POST['password'];
                /** @var \Core\_authentication\component $auth */
                $auth    =   registry::get('auth');
                self::$content = $auth->get('authorization')->login($login, $password);

            }
        } else {
            if (isset($_COOKIE['uid'])) {
                self::redirect(self::$applicationRoute->getURL());
            }
            resources::setCss(self::getTemplate('css/enter.css'));
            resources::setJs(self::getTemplate('js/enter.js'));
        }
    }
}