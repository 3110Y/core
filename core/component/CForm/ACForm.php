<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 30.11.2017
 * Time: 16:29
 */

namespace core\component\CForm;
use core\core;


/**
 * Class ACForm
 *
 * @package core\component\CForm
 */
abstract class ACForm
{
    /** @var \core\component\database\driver\PDO\component */
    protected static $db;

    /** @var \application\admin\controllers\page */
    protected static $controller;

    /** @var string */
    protected static $table;

    /** @var string  */
    protected static $caption = 'Список';

    /** @var string  */
    protected static $mode = 'listing';

    /** @var int  */
    protected static $id = 0;

    /** @var  array */
    protected static $subURL = Array();

    /**
     * @var int
     */
    protected static $subURLNow = 0;

    /**
     * @var bool
     */
    protected static $isWork = true;


    /**
     * @var array просмотрщик
     */
    protected  $viewerConfig        =   Array();


}