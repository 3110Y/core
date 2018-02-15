<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace application\admin\controllers\system\common;


use core\application\AControllers;
use core\router\route;


/**
 * Class front
 * @package application\admin\controllers
 */
class front extends AControllers
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
    	$url = self::$applicationRoute->getURL() === '/' ?   self::$applicationRoute->getURL()   :   self::$applicationRoute->getURL() . '/';
        self::redirect($url .'page');
    }

}
