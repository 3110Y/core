<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:13
 */

namespace core\component\CForm;


class AAction extends ACForm
{
    /**
     * @var array
     */
    protected $config = Array();

    /**
     * @var array
     */
    protected $data = Array();


    /**
     * AAction constructor.
     * @param array $config
     * @param array $data
     */
    public function __construct($config = Array(), $data = Array())
    {
        $this->config = $config;
        $this->data = $data;
    }
}