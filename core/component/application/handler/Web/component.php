<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 02.04.17
 * Time: 23:24
 */

namespace core\component\application\handler\Web;
use core\component\application\connector as applicationConnector;

/**
 * Class component
 * @package core\component\application\handler\Web
 */
class component extends applicationConnector\AConnector implements applicationConnector\IConnector
{
    /**
     * @const float Версия
     */
    const VERSION   =   1.0;
}