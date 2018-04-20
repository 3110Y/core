<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.12.2017
 * Time: 17:09
 */

namespace Core\_library\vendor\html5sortable;


use Core\_library as library;


/**
 * Class comonent
 * @package core\library\vendor\html5sortable
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
        'top'  =>  Array(
            'js/html.sortable.min.js'
        ),
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