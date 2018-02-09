<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 15:33
 */

namespace application\controllers\system\common;


use core\component\application\AControllers;


/**
 * Class front
 * @package application\controllers
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
     */
    public function __construct()
    {
    	$url = self::$application['url'] === '/' ?   self::$application['url']   :   self::$application['url'] . '/';
        self::redirect($url .'page');
    }

}
