<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 27.12.2017
 * Time: 20:05
 */

namespace core\component\library\vendor\JSColor;


use core\component\library as library;


/**
 * Class component
 * @package core\component\library\vendor\JSColor
 */
class component extends library\AVendor implements library\IVendor
{
    /**
     * @const float Версия
     */
    const VERSION   =   1.0;



    public function __construct()
    {
        $this->dir = __DIR__;
    }

    /**
     * @var array
     */
    protected $js = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(
            'js/jscolor/jscolor.min.js'
        ),
    );

    /**
     * @var array
     */
    protected $css = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );

    /**
     * @param array $data
     *
     * @param string $name
     * @return string
     */
    public function returnInit($data = Array(), $name = 'init.tpl')
    {
        return '';
    }

}