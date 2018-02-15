<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 14.02.18
 * Time: 15:30
 */
if (!class_exists(\core\core::class)) {
    die();
}
return [
    [
        'controller' => \application\admin\router::class,
        'url'       => 'admin',
        'function'  => 'render',
    ],
/*    [
        'controller' => \application\client\router::class,
        'uri'       => '\\',
        'function'  => 'render',
        'site'      => '*.*',
        'theme'     => 'basic',
        'method'    => 'GET',
        'port'      => [80, 443]
    ],*/
];