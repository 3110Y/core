<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 0:49
 */

namespace Core\_library\vendor\select2;


use Core\_library as library;


/**
 * Class component
 * @package core\library\vendor\select2
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
            'js/select2/dist/js/select2.min.js'
        ),
    );

    /**
     * @var array
     */
    protected $css = Array(
        'top'  =>  Array(
            'js/select2/dist/css/select2.min.css'
        ),
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
        return parent::returnInit($data, $name);
    }

}