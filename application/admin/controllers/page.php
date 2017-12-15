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
        /** @var \core\component\database\driver\PDO\component $db */
        $db         =   self::get('db');
        $where      =   Array(
            'status'    => 1
        );
        $row        =   $db->selectRows('core_group','`id`, `name`', $where);
        $list       =   ($row !== false)    ?   $row    :   Array();
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
                'required'          =>  true,
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'urlName',
                'field'             =>  'url',
                'attached'          =>  'name',
                'caption'           =>  'Адрес (URL)',
                'placeholder'       =>  'Адрес (URL)',
                'label'             =>  'Адрес (URL)',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'ckeditor',
                'field'             =>  'content',
                'caption'           =>  'Текст',
                'placeholder'       =>  'Текст',
                'label'             =>  'Текст',
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
            Array(
                'type'              =>  'input',
                'field'             =>  'meta_title',
                'caption'           =>  'META Заголовок',
                'placeholder'       =>  'META Заголовок',
                'label'             =>  'META Заголовок',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'view'  =>  false,
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'input',
                'field'             =>  'meta_keywords',
                'caption'           =>  'META Ключевые слова',
                'placeholder'       =>  'META Ключевые слова',
                'label'             =>  'META Ключевые слова',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'view'  =>  false,
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'input',
                'field'             =>  'meta_description',
                'caption'           =>  'META Описание',
                'placeholder'       =>  'META Описание',
                'label'             =>  'META Описание',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'view'  =>  false,
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'number',
                'field'             =>  'order_in_menu',
                'caption'           =>  'Порядок',
                'placeholder'       =>  'Порядок',
                'label'             =>  'Порядок',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'center',
                    'mode'  =>  'view'
                )
            ),
        );
        $config     =   Array(
            'controller'    =>  $this,
            'db'            =>  self::get('db'),
            'table'         =>  'client_page',
            'caption'       =>  'Страницы',
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


      //  $CForm  =   new CForm\component(self::$content, 'CONTENT');
      //  $CForm->setConfig($config);
      //  $CForm->run();
      //  self::$content  =    $CForm->getIncomingArray();



        $field     =   Array(
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'name',
                'label'             =>  'Название',
                'required'          =>  true,
                'grid'              =>  3
            ),
            Array(
                'type'              =>  'UKNumber',
                'field'             =>  'order_in_menu',
                'label'           =>  'Порядок',

                'grid'              =>  3
            ),
/*            Array(
                'type'              =>  'urlName',
                'field'             =>  'url',
                'attached'          =>  'name',
                'caption'           =>  'Адрес (URL)',
                'placeholder'       =>  'Адрес (URL)',
                'label'             =>  'Адрес (URL)',
                'edit'           =>  Array(
                    'mode'  =>  'edit'
                ),
                'listing'           =>  Array(
                    'align' =>  'left',
                    'mode'  =>  'view'
                )
            ),
            Array(
                'type'              =>  'ckeditor',
                'field'             =>  'content',
                'caption'           =>  'Текст',
                'placeholder'       =>  'Текст',
                'label'             =>  'Текст',
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
            ),*/
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'meta_title',
                'label'           =>  'META Заголовок',
                'grid'              =>  3,
                'listing'           =>  Array(
                    'view'  =>  false,
                ),
            ),
            /*
           Array(
               'type'              =>  'UKInput',
               'field'             =>  'meta_keywords',
               'caption'           =>  'META Ключевые слова',
               'placeholder'       =>  'META Ключевые слова',
               'edit'           =>  Array(
                   'mode'  =>  'edit'
               ),
               'listing'           =>  Array(
                   'align' =>  'left',
                   'view'  =>  false,
                   'mode'  =>  'view'
               )
           ),
           Array(
               'type'              =>  'UKInput',
               'field'             =>  'meta_description',
               'caption'           =>  'META Описание',
               'placeholder'       =>  'META Описание',
               'label'             =>  'META Описание',
               'edit'           =>  Array(
                   'mode'  =>  'edit'
               ),
               'listing'           =>  Array(
                   'align' =>  'left',
                   'view'  =>  false,
                   'mode'  =>  'view'
               )
           ),*/
        );
        $url = implode('/', self::getURL());
        $config     =   Array(
            'controller'    =>  $this,
            'db'            =>  self::get('db'),
            'table'         =>  'client_page',
            'caption'       =>  'Страницы',
            'mode'          =>  'listing',
            'viewer'        =>  Array(
                'listing' => Array(
                    'type'      => 'UKListing',
                    'field'     =>  $field,
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
                    'field'     =>  $field,
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