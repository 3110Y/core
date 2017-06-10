<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.06.17
 * Time: 14:05
 */

namespace core\component\CForm\viewer\dell;


use \core\component\CForm as CForm;


class component extends CForm\AViewer implements CForm\IViewer
{
    public function init()
    {
        $config = self::$config;
        unset($config['viewer']);
        $this->viewerConfig = array_merge($this->viewerConfig, $config);
        $this->schemaField              =  $this->viewerConfig['field'];
    }

    public function run()
    {

    }

}