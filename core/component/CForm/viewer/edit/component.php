<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:38
 */

namespace core\component\CForm\viewer\edit;

use \core\component\{
	CForm,
	templateEngine\engine\simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\viewer\edit
 */
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
        $this->schemaField              =  $this->viewerConfig['field'];
        $this->viewerConfig['id']       =   $this->getID();
        $this->viewerConfig['parent']   =   $this->getParent();
        $this->data    =   $this->fillData();
    }

    public function run()
    {
        $this->answer['FIELDS']  =  Array();
        foreach ($this->schemaField as $key => $field) {
            /** @var \core\component\CForm\field\input\component $fieldComponent */
            $fieldComponent = '\core\component\CForm\field\\' . $field['type'] . '\component';
            $fieldComponent::setData($this->data);
            $fieldComponent  =   new $fieldComponent();
            $fieldComponent->setComponentSchema($field);
            if (isset($field['field'], $this->data[$field['field']])) {
                $fieldComponent->setFieldValue($this->data[$field['field']]);
            }
            $fieldComponent->init();
            $mode   =   'run';
            if (isset($field[self::$mode]['mode']) && method_exists($fieldComponent, $field[self::$mode]['mode'])) {
                $mode   =   $field[self::$mode]['mode'];
            } elseif (isset($field['mode']) && method_exists($fieldComponent, $field['mode'])) {
                $mode   =   $field['mode'];
            }
            $fieldComponent->$mode();
            $answer = $fieldComponent->get();
            if (!isset($answer['COMPONENT'])) {
                $answer['COMPONENT']    = '';
            }
            if (!isset($answer['CLASS'])) {
                $answer['CLASS']    = '';
            }
            if (!isset($answer['STYLE'])) {
                $answer['STYLE']    = '';
            }
            if (!isset($answer['ID'])) {
                $answer['ID']    = '';
            }
            if (!isset($answer['CAPTION'])) {
                $answer['CAPTION']    = '';
            }
            $this->answer['FIELDS'][]     =   Array(
                'COMPONENT'     =>  $answer['COMPONENT'],
                'CLASS'     =>  $answer['CLASS'],
                'STYLE'     =>  $answer['STYLE'],
                'ID'        =>  $answer['ID']
            );
            $this->data     =   $fieldComponent::getData();
        }

        if (
            isset($this->viewerConfig['action'], $this->viewerConfig['action']['item'])
            && !empty($this->viewerConfig['action']['item'])
        ) {
            $itemAction = Array();
            foreach ($this->viewerConfig['action']['item'] as $action => $value) {
                /** @var \core\component\CForm\action\dell\component $actionComponent */
                $actionComponent = '\core\component\CForm\action\\' . $action . '\component';
                $actionComponent::setData($this->data);
                $actionComponent  =   new $actionComponent();
                $actionComponent->setComponentSchema($value);
                $actionComponent->init();
                $mode   =   'run';
                if (isset($value['method']) && method_exists($actionComponent, $value['method'])) {
                    $mode   =   $value['method'];
                }
                $actionComponent->$mode();
                $answer = $actionComponent->get();
                if (!isset($answer['COMPONENT'])) {
                    $answer['COMPONENT']    = '';
                }
                if (!isset($answer['CLASS'])) {
                    $answer['CLASS']        = '';
                }
                if (!isset($answer['STYLE'])) {
                    $answer['STYLE']        = '';
                }
                if (!isset($answer['ID'])) {
                    $answer['ID']           = '';
                }
                $itemAction[]     =   Array(
                    'COMPONENT'     =>  $answer['COMPONENT'],
                    'CLASS'         =>  $answer['CLASS'],
                    'STYLE'         =>  $answer['STYLE'],
                    'ID'            =>  $answer['ID']
                );
                $this->data     =   $actionComponent::getData();
            }

            $this->answer['ACTION_ITEM']     =   $itemAction;
            $this->answer['CLASS_ACTION_ITEM'] = '';
        } else {
            $this->answer['CLASS_ACTION_ITEM'] = 'is-hidden ';
        }

        if (
            isset($this->viewerConfig['action'], self::$config['action']['bottomItem'])
            && !empty($this->viewerConfig['action']['bottomItem'])
        ) {
            $itemAction = Array();
            foreach ($this->viewerConfig['action']['bottomItem'] as $action => $value) {
                /** @var \core\component\CForm\action\dell\component $actionComponent */
                $actionComponent = '\core\component\CForm\action\\' . $action . '\component';
                $actionComponent::setData($this->data);
                $actionComponent  =   new $actionComponent();
                $actionComponent->setComponentSchema($value);
                $actionComponent->init();
                $mode   =   'run';
                if (isset($value['method']) && method_exists($actionComponent, $value['method'])) {
                    $mode   =   $value['method'];
                }
                $actionComponent->$mode();
                $answer = $actionComponent->get();
                if (!isset($answer['COMPONENT'])) {
                    $answer['COMPONENT']    = '';
                }
                if (!isset($answer['CLASS'])) {
                    $answer['CLASS']    = '';
                }
                if (!isset($answer['STYLE'])) {
                    $answer['STYLE']    = '';
                }
                if (!isset($answer['ID'])) {
                    $answer['ID']    = '';
                }
                $itemAction[]     =   Array(
                    'COMPONENT'     =>  $answer['COMPONENT'],
                    'CLASS'         =>  $answer['CLASS'],
                    'STYLE'         =>  $answer['STYLE'],
                    'ID'            =>  $answer['ID']
                );
                $this->data     =   $actionComponent::getData();
            }

            $this->answer['ACTION_BOTTOM_ITEM']     =   $itemAction;

        } else {
            $this->answer['CLASS_ACTION_BOTTOM_ITEM'] = 'is-hidden ';
        }
        $this->answer['ID'] = $this->viewerConfig['id'];
        $template = $this->getViewerTemplate();
        $this->answer   =   simpleView\component::replace($template, $this->answer);

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