<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 4:38
 */

namespace application\admin\model;


use \core\component\{
    application\handler\Web             as applicationWeb,
    CForm
};


/**
 * Class CFormDefault
 * @package application\admin\model
 */
class CFormDefault extends applicationWeb\AClass
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
            'db'            =>  self::get('db'),
            'table'         =>  $table,
            'caption'       =>  $caption,
            'mode'          =>  'listing',
            'field'         =>  $field,
            'viewer'        =>  Array(
                'listing' => Array(
                    'type'      => 'UKListing',
                    'multi'     =>  'UKActionID',
                    'search'    =>  true,
                    'button'    =>  Array(
                        'row'   =>  Array(
                            Array(
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/edit/{ROW_ID}',
                                'title'     => 'Редактировать',
                                'icon'      => 'pencil',
                                'class'     => 'uk-button-primary uk-button-small',
                            ),
                            Array(
                                'type'  => 'UKButton',
                                'url'  => '{PAGE_URL}/{PARENT_ID}/api/action/delete/run/{ROW_ID}?redirect=' . $url,
                                'title'     => 'Удалить',
                                'icon'      => 'close',
                                'class'     => 'uk-button-danger  uk-button-small',
                            )
                        ),
                        'rows'  =>  Array(
                            Array(
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/insert?2&redirect={PAGE_URL}/{PARENT_ID}/edit/',
                                'text'      => 'Добавить',
                                'icon'      => 'plus',
                                'class'     => 'uk-button-primary',
                            ),
                            Array(
                                'type'      => 'UKButtonSubmitAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/delete/many?redirect=' . $url,
                                'text'      => 'Удалить',
                                'icon'      => 'close',
                                'form'      =>  '#form-listing',
                                'class'     => 'uk-button-danger',
                            ),
                        ),
                    )
                ),
                'edit' => Array(
                    'type'      => 'UKEdit',
                    'caption'       =>  $caption .': Редактирование',
                    'button'    =>  Array(
                        'rows'  =>  Array(
                            Array(
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/listing',
                                'text'      => 'Вернуться',
                                'icon'      => 'reply',
                                'class'     => 'uk-button-default',
                            ),
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