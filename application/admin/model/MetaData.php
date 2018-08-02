<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 02.08.2018
 * Time: 18:52
 */

namespace application\admin\model;


use core\component\application\AClass;

class MetaData extends AClass
{
    /**
     * Поле под meta data
     *
     * @return array
     */
    private static function field(): array
    {
        return array_merge([
            'type'      =>  'UKTextarea',
            'grid'      =>  '1-1',
            'listing'   =>  [
                'view'      =>  false,
            ],
            'attrs'     => [
                'maxlength' => 300
            ]
        ],...\func_get_args());
    }

    /**
     * Поле META Заголовок
     *
     * @param array $data
     * @return array
     */
    public static function getCFormTitleFieldData(array $data = []): array
    {
        return self::field([
            'type'  =>  'UKInput',
            'field' =>  'meta_title',
            'label' =>  'META Заголовок',
            'attrs'     => [
                'maxlength' => 180
            ]
        ],$data);
    }

    /**
     * Поле META Описание
     *
     * @param array $data
     * @return array
     */
    public static function getCFormDescriptionFieldData(array $data = []): array
    {
        return self::field([
            'type'  =>  'UKTextarea',
            'field' =>  'meta_description',
            'label' =>  'META Описание',
        ],$data);
    }

    /**
     * Поле META Ключевые слова
     *
     * @param array $data
     * @return array
     */
    public static function getCFormKeywordsFieldData(array $data = []): array
    {
        return self::field([
            'type'  =>  'UKTextarea',
            'field' =>  'meta_keywords',
            'label' =>  'META Ключевые слова',
        ],$data);
    }

    /**
     * Все 3 поля
     *
     * @return array
     */
    public static function getCFormFieldsData(): array
    {
        return [
            self::getCFormTitleFieldData(),
            self::getCFormDescriptionFieldData(),
            self::getCFormKeywordsFieldData(),
        ];
    }
}