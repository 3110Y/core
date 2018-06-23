<?php
namespace application\cron\controllers;

use \core\component\application\AControllers;

class noPage extends AControllers
{
    public function __construct()
    {
        header('HTTP/1.0 404 Not Found');
    }
}