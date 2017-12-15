<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 0:49
 */

namespace core\component\library\vendor\CKEditor;


use core\component\library as library;

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
        'top'  =>  Array(
            'ckeditor/ckeditor.js'
        ),
        'bottom'  =>  Array(),
    );

    /**
     * @var array
     */
    protected $css = Array(
        'top'  =>  Array(
            'ckeditor/contents.css'
        ),
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