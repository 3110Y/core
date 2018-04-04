<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\application\controller;


/**
 * Class AControllers
 * @package core\application
 */
abstract class AController
{
    /**
     * @var bool Колличество подуровней
     */
	public static $isSetSubURL  =   false;

    /**
     * @var string шаблон
     */
    public  $template = 'basic';

    /**
     * @var array структура контента
     */
    protected static $content = Array();
}
