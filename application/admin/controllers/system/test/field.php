<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.12.2017
 * Time: 14:46
 */

namespace application\admin\controllers\system\test;

use application\admin\model;
use core\component\application\AControllers;


/**
 * Class field
 * @package application\controllers\system\test
 */
class field extends AControllers
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
                'type'              =>  'UKTextarea',
                'field'             =>  'UKTextarea',
                'label'             =>  'UKTextarea',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'UKSelect',
                'label'             =>  'UKSelect',
                'grid'              =>  '1-1',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
            Array(
                'type'              =>  'UKInput',
                'field'             =>  'UKInput',
                'label'             =>  'UKInput',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'UKSelect',
                'field'             =>  'UKSelect_multiple',
                'label'             =>  'UKSelect_multiple',
                'grid'              =>  '1-1',
                'multiple'          =>  'multiple',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
            Array(
                'type'              =>  'UKNumber',
                'field'             =>  'UKNumber',
                'label'             =>  'UKNumber',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'select2',
                'field'             =>  'select2',
                'label'             =>  'select2',
                'grid'              =>  '1-1',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
            Array(
                'type'              =>  'UKPassword',
                'field'             =>  'UKPassword',
                'label'             =>  'UKPassword',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'select2',
                'field'             =>  'select2_multiple',
                'label'             =>  'select2_multiple',
                'grid'              =>  '1-1',
                'multiple'          =>  'multiple',
                'required'          =>  true,
                'list'              =>  $listStatus,
            ),
            Array(
                'type'              =>  'UKURIName',
                'field'             =>  'UKURIName',
                'label'             =>  'UKURIName',
                'attached'          =>  'UKInput',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'JSColor',
                'field'             =>  'JSColor',
                'label'             =>  'JSColor',
                'required'          =>  true,
                'grid'              =>  '1-1'
            ),
            Array(
                'type'              =>  'CKEditor',
                'field'             =>  'CKEditor',
                'label'             =>  'CKEditor',
                'grid'              =>  '1-1',
            ),
            Array(
                'type'              =>  'UKImageUpload',
                'field'             =>  'UKImageUpload',
                'label'             =>  'UKImageUpload',
                'grid'              =>  '1-1',
                'path'              =>  'field_one'
            ),
            Array(
                'type'              =>  'UKGalleryUploadMultiple',
                'table'             =>  Array(
                    'link'      =>  'test_field_photo',
                ),
                'label'             =>  'UKGalleryUploadMultiple',
                'grid'              =>  '1-1',
                'path'              =>  'field_more'
            ),
        );
        self::$content  =    model\CFormDefault::generation($this, 'test_field', 'Поля', $field);
    }
}