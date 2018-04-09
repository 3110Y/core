<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\application\controller;
use core\application\application;


/**
 * Class AControllers
 * @package core\application
 */
abstract class AController
{

    /**
     * @var string шаблон
     */
    protected  $template = 'basic';


    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return application::getTemplate($this->template);
    }


}
