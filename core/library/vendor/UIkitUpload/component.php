<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.12.2017
 * Time: 17:09
 */

namespace core\library\vendor\UIkitUpload;


use core\library as library;


/**
 * Class comonent
 * @package core\library\vendor\UIkitUpload
 */
class component extends library\AVendor implements library\IVendor
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
        'bottom'  =>  Array(),
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
        return parent::returnInit($data, $name);
    }

}