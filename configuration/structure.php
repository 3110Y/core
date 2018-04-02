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
        'access'    => [
            'mode'  => 'disallow',
            'group' =>  [1],
            'user'  =>  [1]
        ]
    ],
    [
        'controller' => \application\client\router::class,
        'uri'       => '\\',
        'function'  => 'render',
        'site'      => '*',
        'method'    => 'GET',
        'port'      => [80, 443],
        'access'    => [
            'mode'  => 'allow',
            'group' =>  [],
            'user'  =>  []
        ]
    ],
];