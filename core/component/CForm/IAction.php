<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:13
 */

namespace core\component\CForm;


interface IAction
{
    public function run($id = 0);
}