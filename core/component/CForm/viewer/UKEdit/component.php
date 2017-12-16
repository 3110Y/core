<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 02.12.2017
 * Time: 16:54
 */

namespace core\component\CForm\viewer\UKEdit;


use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView
};
use core\core;

/**
 * Class component
 *
 * @package core\component\CForm\viewer\UKEdit
 */
class component extends CForm\AViewer implements CForm\IViewer
{
    /**
     * @const float Версия
     */
    const VERSION   =   2;



    public function init()
    {
        parent::init();
        $this->answer['PARENT']             = $this->parent;
        $this->answer['CAPTION']            = parent::$caption;

    }

    /**
     * Запуск
     */
    public function run()
    {
        if (empty($this->data)) {
            $template = core::getDR(true) . self::getTemplate('template/formNo.tpl', __DIR__);
        } else {
            $template = core::getDR(true) . self::getTemplate('template/form.tpl', __DIR__);
            $this->answer['FIELDS'] = Array();
            foreach ($this->field as $key => $field) {

                if (!isset($field['field']) && is_string($key)) {
                    $field['field'] = $key;
                }
                if (isset($field['field'], $this->data[$field['field']])) {
                    $field['value'] = $this->data[$field['field']];
                }
                if (!isset($field['mode'])) {
                    $field['mode'] = 'edit';
                }
                if (isset($field['type'])) {
                    $fieldName = $field['type'];
                    $fieldObject = "core\component\CForm\\field\\{$fieldName}\component";
                    if (class_exists($fieldObject)) {
                        /** @var \core\component\CForm\field\UKInput\component $fieldComponent */
                        $fieldComponent = new $fieldObject($field, $this->data);
                        $fieldComponent->init();
                        $this->answer['FIELDS'][] = Array(
                            'COMPONENT' => $fieldComponent->getAnswer()
                        );
                    }
                }
            }

            /**
             * Кнопки
             */
            if (isset($this->button['rows']) && !empty($this->button['rows'])) {
                foreach ($this->button['rows'] as $key => $button) {
                    if (isset($button['type'])) {
                        $buttonName = $button['type'];
                        $buttonObject = "core\component\CForm\\button\\{$buttonName}\component";
                        if (class_exists($buttonObject)) {
                            /** @var \core\component\CForm\button\UKButton\component $fieldComponent */
                            $buttonComponent = new $buttonObject($button, $this->data);
                            $buttonComponent->init();
                            $buttonComponent->run();
                            $this->answer['ROWS'][] = Array(
                                'COMPONENT' =>  $buttonComponent->getAnswer()
                            );
                        }
                    }
                }
            }

        }

        self::$controller::setCss(self::getTemplate('css/form.css', __DIR__));
        $this->answer   =   simpleView\component::replace($template, $this->answer);
    }



    protected function fillData()
    {
        $where  = $this->config['where']  ??  Array();
        $where['parent_id'] =   $this->parent;
        $where['id']        =   $this->page;
        $fields =   Array(
            'id'
        );
        foreach ($this->field as $item) {
            if (isset($item['field'])) {
                $fields[] = $item['field'];
            }
        }
        array_unique($fields);
        return self::$db->selectRow(self::$table, $fields, $where);
    }

}