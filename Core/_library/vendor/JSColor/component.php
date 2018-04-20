<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 27.12.2017
 * Time: 20:05
 */

namespace Core\_library\vendor\JSColor;


use Core\_library as library;


/**
 * Class component
 * @package core\library\vendor\JSColor
 */
class component extends _library\AVendor implements _library\IVendor
{



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