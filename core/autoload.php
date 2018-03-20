<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.02.18
 * Time: 17:56
 */

include_once 'dir' . DIRECTORY_SEPARATOR .  'dir.php';
include_once 'autoloader' . DIRECTORY_SEPARATOR .  'autoloader.php';

use core\autoloader\autoloader;

autoloader::getInstance()->register();
autoloader::getInstance()->addNamespace('core', __DIR__);