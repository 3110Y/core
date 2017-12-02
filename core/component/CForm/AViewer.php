<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:47
 */

namespace core\component\CForm;
use core\core;
/**
 * Class AViewer
 *
 * @package core\component\CForm
 */
abstract class AViewer extends ACForm
{
    /**
     * @var mixed
     */
    protected $answer;

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

}