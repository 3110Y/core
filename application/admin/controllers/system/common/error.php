<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace application\admin\controllers\system\common;


use \core\{
    application\controller\AController,
    router\route
};


/**
 * Class error
 * @package application\admin\controllers
 */
class error extends AController
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   0;

    /**
     * @var string
     */
    public $template = 'form';

    /**
     * Инициализация
     * @param route $route
     */
    public function __construct(route $route)
    {
        header('HTTP/1.0 404 Not Found');
        self::$content['CONTENT']  =    'Ошибка';
    }

}
