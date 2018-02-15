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
/*    [
        'controller' => \application\admin\controllers\system\common\front::class,
        'url' => '/',
    ],*/
    [
        'controller' => \application\admin\controllers\system\common\basic::class,
        'url' => '',
    ],
];