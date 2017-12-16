<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 8:47
 */

namespace application\admin\controllers;

use \core\component\{
    application\handler\Web as applicationWeb,
    CForm
};


/**
 * Class page
 * @package application\admin\controllers
 */
class page extends applicationWeb\AControllers implements applicationWeb\IControllers
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
        $field     =   Array(
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'name',
                'label'             =>  'Название',
                'required'          =>  true,
                'grid'              =>  '2-5'
            ),
            Array(
                'type'              =>  'UKURIName',
                'field'             =>  'url',
                'attached'          =>  'name',
                'label'             =>  'Адрес (URL)',
                'grid'              =>  '1-5',
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'status',
                'label'             =>  'Статус',
                'grid'              =>  '1-5',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
            Array(
                'type'              =>  'UKNumber',
                'field'             =>  'order_in_menu',
                'label'             =>  'Порядок',
                'grid'              =>  '1-5',
                'listing'           =>  Array(
                    'order'  =>  999,
                ),
            ),
            Array(
                'type'              =>  'CKEditor',
                'field'             =>  'content',
                'label'             =>  'Текст',
                'grid'              =>  '1-1',
            ),
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'meta_title',
                'label'             =>  'META Заголовок',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'meta_keywords',
                'label'             =>  'META Ключевые слова',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),
            Array(
                'type'              =>  'UKTextarea',
                'field'             =>  'meta_description',
                'label'             =>  'META Описание',
                'grid'              =>  '1-1',
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),


            /**/
        );
        $url = implode('/', self::getURL());
        $config     =   Array(
            'controller'    =>  $this,
            'db'            =>  self::get('db'),
            'table'         =>  'client_page',
            'caption'       =>  'Страницы',
            'mode'          =>  'listing',
            'field'         =>  $field,
            'viewer'        =>  Array(
                'listing' => Array(
                    'type'      => 'UKListing',
                    'multi'     =>  'UKActionID',
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
        $CForm  =   new CForm\component(self::$content, 'CONTENT');
        $CForm->setConfig($config);
        $CForm->run();
        self::$content  =    $CForm->getIncomingArray();

    }

}