<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 0:57
 */

namespace application\admin\controllers;

use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};


class group extends applicationWeb\AControllers implements applicationWeb\IControllers
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
        $listStatus =   Array(
            Array(
                'id'    =>  1,
                'name'  => 'Активно'
            ),
            Array(
                'id'    =>  2,
                'name'  => 'Неактивно'
            ),
            Array(
                'id'        =>  3,
                'name'      => 'Черновик',
                'disabled'  =>  true
            ),
        );
        $schema     =   Array(
            Array(
                'type'              =>  'input',
                'field'             =>  'name',
                'caption'           =>  'Название',
                'placeholder'       =>  'Название',
                'label'             =>  'Название',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'select',
                'field'             =>  'status',
                'caption'           =>  'Статус',
                'placeholder'       =>  'Статус',
                'label'             =>  'Статус',
                'list'              =>  $listStatus,
                'NoZero'            =>  true,
                'edit'              =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'right',
                    'mode'  =>  'view'
                )
            ),
        );
        $config     =   Array(
            'controller'    =>  $this,
            'db'            =>  self::get('db'),
            'table'         =>  'core_group',
            'caption'       =>  'Группы',
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
                    'action'            =>  Array(
                        'row'       =>  Array(
                            'edit'      =>  Array(
                                'method'    =>  'one'
                            ),
                            'dell'      =>   Array(
                                'method'    =>  'one'
                            ),
                        ),
                        'rows'      =>  Array(
                            'add'       =>  Array(
                                'method'    =>  'many'
                            ),
                            'dell'      =>  Array(
                                'method'    =>  'many'
                            ),
                        ),
                    ),
                ),
                'edit'      =>  Array(
                    'viewer'            =>  'edit',
                    'template'          =>  'block/form/form.tpl',
                    'templateNoData'    =>  'block/form/formNo.tpl',
                    'css'               =>  Array(
                        Array(
                            'file'  =>  'block/form/css/style.css'
                        ),
                        Array(
                            'file'  =>  'block/form/css/form.css'
                        ),
                    ),
                    'field'             =>  $schema,
                    'action'            =>  Array(
                        'item'    =>  Array(
                            'back'      =>   Array(
                                'method'    =>  'one',
                                'redirect'  =>  '/listing/{PAGE}',
                            ),
                            'save'      =>  Array(
                                'method'    =>  'one',
                            ),
                        ),
                    ),
                ),
                'dell'      =>  Array(
                    'viewer'            =>  'dell',
                    'field'             =>  $schema,
                ),
                'save'      =>  Array(
                    'viewer'            =>  'save',
                    'field'             =>  $schema,
                ),
                'add'      =>  Array(
                    'viewer'            =>  'add',
                    'field'             =>  $schema,
                    'redirect'          =>  '/edit/{DATA_ID}',
                    'status'            =>  3
                ),
            ),

        );


        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();

    }

}