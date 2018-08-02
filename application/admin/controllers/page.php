<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 8:47
 */

namespace application\admin\controllers;

use application\admin\model;
use \core\component\{
    application\AControllers
};


/**
 * Class page
 * @package application\controllers
 */
class page extends AControllers
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
        [$title, $description, $keywords] = model\MetaData::getCFormFieldsData();
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
            $title,
            $description,
            $keywords,


            /**/
        );
        self::$content  =    model\CFormDefault::generation($this, 'client_page', 'Страницы', $field);

    }

}