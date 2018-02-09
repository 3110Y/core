<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.02.18
 * Time: 17:56
 */

include_once 'component' . DIRECTORY_SEPARATOR . 'autoloader' . DIRECTORY_SEPARATOR .  'autoloader.php';

use core\component\autoloader\autoloader;

autoloader::getInstance()->register();
autoloader::getInstance()->addNamespace('core', __DIR__);