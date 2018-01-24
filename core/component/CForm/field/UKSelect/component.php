<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 3:34
 */

namespace core\component\CForm\field\UKSelect;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView
};
use Seld\JsonLint\Lexer;


/**
 * Class component
 * @package core\component\CForm\field\UKSelect
 */
class component extends CForm\AField implements CForm\IField
{


    private $multiple   = false;
    private $list       = Array();


    public function init()
    {
        parent::init();
        if (isset($this->configField['multiple']) && $this->configField['multiple']) {
            $this->multiple = true;
            unset($this->configField['multiple']);
        }
        $data['TD']                         =   '';
        $data['GRID']                       =   1;
        $data['PLACEHOLDER']                =   '';
        $list                               =   $this->configField['list'] ??   Array();
        if (isset($this->configField['list'])) {
            unset($this->configField['list']);
        }
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        $data['MULTIPLE']       =   $this->multiple ?   'multiple'  :   '';
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['READONLY']       =   $this->readonly     ?   'disabled'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['ID_NAME']        =   $this->multiple ?   $this->idField . '[]'   :   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span class="uk-text-center">';
        $data['HREF_TWO']       =   $data['HREF'] == '<span class="uk-text-center">'   ?    '</span>'                    :   '</a>';
        $data['VALUE_NAME']     =   Array();
        $this->value            =   $this->getFieldValue();
        foreach ($list as $key => $value) {
            if (!isset($value['id'])) {
                $value['id'] = $key;
            }
            $selected = false;
            foreach ($this->value as $v) {
                if ($v == $value['id']) {
                    $selected = true;
                    break;
                }
            }
            $this->list[$key] = Array(
                'ID'        =>  $value['id'],
                'NAME'      =>  isset($value['name'])                               ?   $value['name']      :   $value['id'],
                'DISABLED'  =>  isset($value['disabled'])   &&  $value['disabled']  ?   'disabled'          :   '',
                'SELECTED'  =>  $selected                       ?   'selected'          :   '',
            );
            if ($selected) {
                $data['VALUE_NAME'][] = Array(
                    'NAME'  =>  $this->list[$key]['NAME'],
                );
            }

        }
        if (empty($data['VALUE_NAME'])) {
            $data['VALUE_NAME'][] = Array(
                'NAME'  =>  'Не выбрано',
            );
        }
        $data['LIST']           =   $this->list;
        $data['HREF']           =   simpleView\component::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView\component::replace($this->template, $data);
    }

    public function view()
    {
        $this->template     =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function edit()
    {
        $this->template     =   self::getTemplate('template/edit.tpl', __DIR__);

    }

    public function preInsert()
    {
        if (isset($this->configField['table'])) {
            return false;
        } else {
            return implode(',', $this->value);
        }
    }

    public function preUpdate()
    {
        if (isset($this->configField['table'])) {
            $this->postDelete();
            foreach ($this->row[$this->configField['table']['link']] as $table) {
                $value = Array(
                    $this->configField['table']['field_id'] =>  $this->row[$this->configField['table']['field']],
                    $this->configField['table']['table_id'] =>  $table
                );
                parent::$db->inset($this->configField['table']['link'], $value);
            }
            $this->value =  false;
        } else {
            $this->value = implode(',', $this->value);
        }
        return parent::preUpdate();
    }

    public function postDelete()
    {
        if (isset($this->configField['table'])) {
            $where = Array();
            $where[] = Array(
                'field' => $this->configField['table']['field_id'],
                'value' => $this->row[$this->configField['table']['field']]
            );
            parent::$db->dell($this->configField['table']['link'], $where);
        }
        return parent::postDelete();
    }

    /**
     * @return array|mixed
     */
    private function getFieldValue()
    {
        if (isset($this->configField['table'])) {
            $this->configField['table']['field']        =   $this->configField['table']['field']    ??  'id';
            $field = Array(
                Array(
                    'field' => $this->configField['table']['table_id'],
                    'as'    => 'id'
                ),
            );
            $where = Array(
                $this->configField['table']['field_id'] => $this->row[$this->configField['table']['field']]
            );
            $rows = parent::$db->selectRows($this->configField['table']['link'], $field, $where);
            $array = Array();
            if ($rows !== false) {
                foreach ($rows as $row) {
                    $array[] = $row['id'];
                }
            }
            $this->value =  $array;
        }
        if ($this->value === '' ) {
            return Array();
        }
        if (is_array($this->value)) {
            return $this->value;
        }
        if ($this->multiple) {
            return explode(',', $this->value);
        } else {
            return Array(
                $this->value
            );
        }
    }
}