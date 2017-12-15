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

    /**
     * @var array
     */
    protected static $js = Array(
        'top'  =>  Array(
            'vendor/ckeditor/contents.css'
        ),
        'bottom'  =>  Array(),
    );

    /**
     * @var array
     */
    protected static $css = Array(
        'top'  =>  Array(
            'ckeditor/ckeditor.js'
        ),
        'bottom'  =>  Array(),
    );

    /**
     * @param array $data
     * @param string $dir
     * @return string
     */
    public static function returnInit($data = Array(), $dir = __DIR__)
    {
        return parent::returnInit($data, __DIR__);
    }

}