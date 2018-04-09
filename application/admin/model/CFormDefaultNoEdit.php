<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.01.2018
 * Time: 13:00
 */

namespace application\admin\model;


use \core\{
    CForm,
    registry\registry
};


/**
 * Class CFormDefaultNoEdit
 * @package application\model
 */
class CFormDefaultNoEdit
{
    /**
     * @param object $controller
     * @param string $table
     * @param string $caption
     * @param array $field
     * @return array|bool|mixed
     */
    public static function generation($controller, $table, $caption, $field)
    {
        $url = implode('/', $controller::getURL());
        $config     =   Array(
            'controller'    =>  $controller,
            'db'            =>  registry::get('db'),
            'table'         =>  $table,
            'caption'       =>  $caption,
            'mode'          =>  'listing',
            'field'         =>  $field,
            'viewer'        =>  Array(
                'listing' => Array(
                    'type'      => 'UKListing',
                    'search'    =>  true,
                    'button'    =>  Array(
                        'row'   =>  Array(),
                        'rows'  =>  Array(),
                    )
                ),
            )
        );
        $CForm  =   new CForm\component($controller::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->run();
        return $CForm->getIncomingArray();
    }
}