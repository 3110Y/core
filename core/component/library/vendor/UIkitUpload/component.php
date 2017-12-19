<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.12.2017
 * Time: 17:09
 */

namespace core\component\library\vendor\UIkitUpload;


use core\component\library as library;


/**
 * Class comonent
 * @package core\component\library\vendor\UIkitUpload
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
     * @return string
     */
    public function returnInit($data = Array())
    {
        return parent::returnInit($data);
    }

}