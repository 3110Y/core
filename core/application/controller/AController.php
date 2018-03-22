<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\application\controller;

use \core\application\AApplication;

/**
 * Class AControllers
 * @package core\application
 */
abstract class AController extends AApplication
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
	public static $isSetSubURL  =   false;

    /**
     * @var string шаблон
     */
    public  $template = 'basic';

}
