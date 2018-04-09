<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.12.2017
 * Time: 13:03
 */

namespace application\admin\model;


use core\{
    CForm,
    registry\registry
};


/**
 * Class CFormOne
 * @package application\model
 */
class CFormOne
{
    public static function generation($controller, $table, $caption, $field, $id = 1)
    {
        $config     =   Array(
            'controller'    =>  $controller,
            'db'            =>  registry::get('db'),
            'table'         =>  $table,
            'caption'       =>  $caption,
            'mode'          =>  'edit',
            'field'         =>  $field,
            'viewer'        =>  Array(
                'edit' => Array(
                    'type'      => 'UKEdit',
                    'where'     =>  Array(
                        'id' => $id
                    ),
                    'caption'   =>  $caption,
                    'button'    =>  Array(
                        'rows'  =>  Array(
                            Array(
                                'type'      => 'UKButtonAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/update/run/{ROW_ID}',
                                'text'      => 'Сохранить',
                                'icon'      => 'check',
                                'success'   => 'Изменения сохранены',
                                'error'     => 'Изменения не сохранены',
                                'class'     => 'uk-button-primary',
                            )
                        ),
                    ),
                ),
            )
        );
        $CForm  =   new CForm\component($controller::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->run();
        return $CForm->getIncomingArray();
    }
}