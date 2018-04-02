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
use \application\admin\controllers;
return [
    [
        'controller' => controllers\system\common\front::class,
        'url' => '/',
    ],
    [
        'controller' => controllers\system\rules\user::class,
        'url' => 'users-and-roles/user',
    ],
    [
        'controller' => controllers\system\rules\group::class,
        'url' => 'users-and-roles/group',
    ],
    [
        'controller' => controllers\system\rules\rulesObjects::class,
        'url' => 'users-and-roles/rules-objects',
    ],
    [
        'controller' => controllers\system\rules\rules::class,
        'url' => 'users-and-roles/rules',
    ],
    [
        'controller' => controllers\system\rules\usersRoles::class,
        'url' => 'users-and-roles/users-and-roles',
    ],
    [
        'controller' => controllers\system\common\enter::class,
        'url' => 'enter',
    ],
    [
        'controller' => controllers\system\common\logout::class,
        'url' => 'logout',
    ],
    [
        'controller' => controllers\system\common\settings::class,
        'url' => 'page',
    ],
    [
        'controller' => controllers\system\common\settings::class,
        'url' => 'settings',
    ],
    [
        'controller' => controllers\system\test\test::class,
        'url' => 'test',
    ],
    [
        'controller' => controllers\system\test\field::class,
        'url' => 'test/field',
    ],
    [
        'controller' => controllers\system\common\error::class,
        'url' => '*',
    ],
];