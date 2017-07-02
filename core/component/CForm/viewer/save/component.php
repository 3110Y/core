<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.06.17
 * Time: 14:05
 */

namespace core\component\CForm\viewer\save;


use \core\component\CForm as CForm;


class component extends CForm\AViewer implements CForm\IViewer
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.0;


	public function init()
    {
        $config = self::$config;
        unset($config['viewer']);
        $this->viewerConfig = array_merge($this->viewerConfig, $config);
        if (self::$countSubURL >= 3) {
            $this->schemaField = Array();
            foreach ($this->viewerConfig['field'] as $value) {
                if (isset($value['unique']) && $value['unique'] === self::$subURL[2]) {
                    $this->schemaField[] = $value;
                }
            }
        } else {
            $this->schemaField = $this->viewerConfig['field'];
        }
        $this->viewerConfig['id']       =   $this->getID();
        $this->viewerConfig['parent']   =   $this->getParent();
        $this->data                     =   $this->fillData();
    }

    public function run()
    {
        $error  = Array(
            'danger'    => Array(),
            'warning'  => Array()
        );
        $value   = Array();
        /** поля для пре обновления */
        foreach ($this->schemaField as $key => $field) {
            /** @var \core\component\CForm\field\input\component $fieldComponent */
            $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
            $fieldComponent::setData($this->data);
            $fieldComponent  =   new $fieldComponent();
            $fieldComponent->setComponentSchema($field);
            if (isset($_POST[$field['field']])) {
                $fieldComponent->setFieldValue($_POST[$field['field']]);
            }
            $fieldComponent->setField($this->field);
            $fieldComponent->init();
            $answer = Array();
            if (method_exists($fieldComponent, 'preUpdate')) {
                $answer =    $fieldComponent->preUpdate();
            }
            if (isset($answer['value']) && $answer['value'] === false) {
                unset($value[$field['field']]);
            } elseif (isset($answer['value'])) {
                $value[$field['field']] = $answer['value'];
            } elseif (isset($_POST[$field['field']])) {
                $value[$field['field']] = $_POST[$field['field']];
            }
            if (isset($answer['error'])) {
                $error['danger'][] = $answer['error'];
            }
        }
        if (!empty($error['danger'])) {
            $this->answer   =   $error;
            return $this->answer;
        }
        $where = Array(
            'id'    =>  $this->viewerConfig['id']
        );
        if (!empty($value)) {
            /** @var \core\component\database\driver\PDO\component $db */
            $db = $this->viewerConfig['db'];
            $db->update($this->viewerConfig['table'], $value, $where);
            $this->viewerConfig['id'] = $db->getLastID();
            $this->data = $db->selectRow($this->viewerConfig['table'], $this->field, $where);
        }
        /** поля для пост обновления */
        foreach ($this->schemaField as $key => $field) {
            /** @var \core\component\CForm\field\input\component $fieldComponent */
            $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
            $fieldComponent::setData($this->data);
            $fieldComponent  =   new $fieldComponent();
            $fieldComponent->setComponentSchema($field);
            if (isset($field['field'], $this->data[$field['field']])) {
                $fieldComponent->setFieldValue($this->data[$field['field']]);
            }
            $fieldComponent->setField($this->field);
            $fieldComponent->init();
            if (method_exists($fieldComponent, 'postUpdate')) {
                $err = $fieldComponent->postUpdate();
                if ($err !== '') {
                    $error['warning'][] = $err;
                }
            }
        }
        if (!empty($error['danger']) || !empty($error['warning'])) {
            $this->answer   =   $error;
        } else {
            $this->answer   =   true;
        }
        return $this->answer;
    }

    /**
     * Заполняет дату
     * @return array дата
     */
    private function fillData(): array
    {
        $this->fillField();
        usort($this->schemaField, Array($this, 'callbackSchemaSort'));
        $where  =   $this->preparationWhere(Array(
            'id'    =>  $this->viewerConfig['id'],
        ));
        /** @var \core\component\database\driver\PDO\component $db */
        $db =   $this->viewerConfig['db'];
        $row    =   $db->selectRow($this->viewerConfig['table'], $this->field, $where);
        return ($row === false) ?   []  :   $db->selectRow($this->viewerConfig['table'], $this->field, $where);
    }
}