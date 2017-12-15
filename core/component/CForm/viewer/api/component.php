<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 11:56
 */

namespace core\component\CForm\viewer\api;


use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};
use core\core;


/**
 * Class component
 *
 * @package core\component\CForm\viewer\api
 */
class component extends CForm\AViewer implements CForm\IViewer
{
    /**
     * @const float Версия
     */
    const VERSION   =   2;

    /**
     * @var string
     */
    private $package = '';

    /**
     * @var string
     */
    private $component = '';

    /**
     * @var string
     */
    private $method = 'run';

    /**
     * @var int
     */
    private $paramID = 0;



    /**
     *
     */
    public function init()
    {

        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->package = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->component = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->method = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->paramID = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
        $this->field        =   self::$viewerConfig['field']            ?? $this->field;
        $this->data         =   self::$viewerConfig['data']             ?? $_POST;
    }

    /**
     * Запуск
     */
    public function run()
    {
        if ($this->package == '' || $this->component == '') {
            parent::$isWork = false;
        } else {
            $package    =   $this->package;
            $component  =   $this->component;
            $method     =   $this->method;
            $component =   "core\component\CForm\\{$package}\\{$component}\component";
            if (class_exists($component)) {
                $component = new $component($this->field, $this->data);
                $component->init();
                $component->$method($this->paramID);
                $this->answer = $component->getAnswer();
            }
        }
    }
}