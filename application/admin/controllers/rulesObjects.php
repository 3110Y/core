<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 19:15
 */

namespace application\admin\controllers;

use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};


/**
 * Class rulesObjects
 * @package application\admin\controllers
 */
class rulesObjects extends applicationWeb\AControllers implements applicationWeb\IControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   false;

    /**
     * Инициализация
     */
    public function init()
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db         =   self::get('db');
        $where      =   Array(
            'status'    => 3
        );
        $row        =   $db->selectRows('core_group','`id`, `name`', $where);
        $list       =   ($row !== false)    ?   $row    :   Array();
        $schema     =   Array(
            Array(
                'type'              =>  'input',
                'field'             =>  'name',
                'caption'           =>  'Название',
                'placeholder'       =>  'Название',
                'label'             =>  'Название',
                'listing'           =>  Array(
                    'align' =>  'left',
                    'mode'  =>  'view'
                ),
            ),
        );
        $config     =   Array(
            'controller'    =>  $this,
            'db'            =>  self::get('db'),
            'table'         =>  'core_rules_objects',
            'caption'       =>  'Объекты правил',
            'defaultMode'   =>  'listing',
            'viewer'        => Array(
                'listing'      =>  Array(
                    'viewer'            =>  'listing',
                    'template'          =>  'block/form/list.tpl',
                    'templateNoData'    =>  'block/form/listNo.tpl',
                    'css'               =>  Array(
                        Array(
                            'file'  =>  'block/form/css/style.css'
                        ),
                        Array(
                            'file'  =>  'block/form/css/list.css'
                        ),
                    ),
                    'field'             =>  $schema,
                    'action'            =>  Array(),
                ),
            ),
        );


        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();

    }

}