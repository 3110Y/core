<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.06.17
 * Time: 14:05
 */

namespace core\component\CForm\viewer\dell;


use \core\component\CForm as CForm;


class component extends CForm\AViewer implements CForm\IViewer
{
    public function init()
    {
        $config = self::$config;
        unset($config['viewer']);
        $this->viewerConfig             =   array_merge($this->viewerConfig, $config);
        $this->schemaField              =   $this->viewerConfig['field'];
        $this->viewerConfig['id']       =   $this->getDellID();
        $this->data                     =   $this->fillData();
    }

    public function run()
    {
        if (is_array($this->viewerConfig['id'])) {
            /** @var \core\component\database\driver\PDO\component $db */
            $db     =   $this->viewerConfig['db'];
            foreach ($this->data as $id => $data) {
                /** поля для пре удаления */
                foreach ($this->schemaField as $key => $field) {
                    /** @var \core\component\CForm\field\input\component $fieldComponent */
                    $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
                    $fieldComponent::setData($this->data);
                    $fieldComponent  =   new $fieldComponent();
                    $fieldComponent->setComponentSchema($field);
                    if (isset($data[$field['field']])) {
                        $fieldComponent->setFieldValue($data[$field['field']]);
                    }
                    $fieldComponent->setField($this->field);
                    $fieldComponent->init();
                    if (method_exists($fieldComponent, 'preDell')) {
                        $fieldComponent->preDell();
                    }
                    $this->field    =   $fieldComponent->getField();
                }
                $where = Array(
                    'id'    =>  $data['id']
                );
                $db->dell($this->viewerConfig['table'], $where);
                /** поля для пост удаления */
                foreach ($this->schemaField as $key => $field) {
                    /** @var \core\component\CForm\field\input\component $fieldComponent */
                    $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
                    $fieldComponent  =   new $fieldComponent();
                    $fieldComponent->setComponentSchema($field);
                    $fieldComponent->init();
                    if (method_exists($fieldComponent, 'postDell')) {
                        $fieldComponent->postDell();
                    }
                }
            }
        } else {
            /** поля для пре удаления */
            foreach ($this->schemaField as $key => $field) {
                /** @var \core\component\CForm\field\input\component $fieldComponent */
                $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
                $fieldComponent::setData($this->data);
                $fieldComponent  =   new $fieldComponent();
                $fieldComponent->setComponentSchema($field);
                if (isset($this->data[$field['field']])) {
                    $fieldComponent->setFieldValue($this->data[$field['field']]);
                }
                $fieldComponent->setField($this->field);
                $fieldComponent->init();
                if (method_exists($fieldComponent, 'preDell')) {
                    $fieldComponent->preDell();
                }
                $this->field    =   $fieldComponent->getField();
            }
            $where = Array(
                'id'    =>  $this->viewerConfig['id']
            );
            /** @var \core\component\database\driver\PDO\component $db */
            $db     =   $this->viewerConfig['db'];
            $db->dell($this->viewerConfig['table'], $where);

            /** поля для пост удаления */
            foreach ($this->schemaField as $key => $field) {
                /** @var \core\component\CForm\field\input\component $fieldComponent */
                $fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
                $fieldComponent  =   new $fieldComponent();
                $fieldComponent->setComponentSchema($field);
                $fieldComponent->init();
                if (method_exists($fieldComponent, 'postDell')) {
                    $fieldComponent->postDell();
                }
            }
        }
        if (isset($_GET['back'])) {
            $url = base64_decode($_GET['back']);
        } else {
            $url = $this->viewerConfig::getPageURL();
        }
        self::redirect($url);
    }

    /**
     * Отдает ID
     * @return mixed|int|array ID
     */
    private function getDellID()
    {
        if (isset($this->viewerConfig['id'])) {
            return (int)$this->viewerConfig['id'];
        }
        if (isset($_POST['row']) && is_array($_POST['row'])) {
            $array = Array();
            foreach ($_POST['row'] as $key => $value) {
                $array[] = (int)$key;
            }
            return $array;
        }
        if (count(self::$subURL) >= 2) {
            return (int)end(self::$subURL);
        }
        return 0;
    }

    /**
     * Заполняет дату
     * @return array дата
     */
    private function fillData(): array
    {
        $this->fillField();
        usort($this->schemaField, Array($this, 'callbackSchemaSort'));
        if (is_array($this->viewerConfig['id'])) {
            $where = array();
            for ($i = 0, $iMax = count($this->viewerConfig['id']); $i < $iMax; $i++) {
                $where[] = Array(
                    'f' => 'id',
                    'v' => $this->viewerConfig['id'][$i]
                );
                if (($iMax - 1) !== $i) {
                    $where[] = 'OR';
                }
            }
        } else {
            $where = Array(
                'id' => $this->viewerConfig['id'],
            );
        }
        $where = $this->preparationWhere($where);
        /** @var \core\component\database\driver\PDO\component $db */
        $db =   $this->viewerConfig['db'];
        if (is_array($this->viewerConfig['id'])) {
            return $db->selectRows($this->viewerConfig['table'], $this->field, $where);
        }
        return $db->selectRow($this->viewerConfig['table'], $this->field, $where);
    }

}