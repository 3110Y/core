<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 8:47
 */

namespace application\admin\controllers;

use application\admin\model as model;
use \core\component\{
    application as application
};


/**
 * Class page
 * @package application\admin\controllers
 */
class page extends application\AControllers
{
    /**
     * @var mixed|int|false Колличество подуровней
     */
    public static $countSubURL  =   false;


    /**
     * Инициализация
     */
    public function __construct()
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
        self::$content  =    model\CFormDefault::generation($this, 'client_page', 'Страницы', $field);

    }

}