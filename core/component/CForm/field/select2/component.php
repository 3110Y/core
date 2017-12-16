<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 3:34
 */

namespace core\component\CForm\field\select2;

use \core\component\{
    CForm,
    library as library,
    templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\select2
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
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['ID_NAME']        =   $this->multiple ?   $this->idField . '[]'   :   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span class="uk-text-center">';
        $data['HREF_TWO']       =   $data['HREF'] == '<span class="uk-text-center">'   ?    '</span>'                    :   '</a>';
        $data['VALUE_NAME']     =   'Не выбрано';
        $this->value            =   $this->getFieldValue();
        foreach ($list as $key => $value) {
            if (!isset($value['id'])) {
                $value['id'] = $key;
            }
            if ($this->multiple) {
                $selected  = false;
                if (is_array($this->value)) {
                    foreach ($this->value as $v) {
                        if ($v == $value['id']) {
                            $selected = true;
                            break;
                        }
                    }
                }
            } else {
                $selected   =   $this->value == $value['id'];
            }
            $this->list[$key] = Array(
                'ID'        =>  $value['id'],
                'NAME'      =>  isset($value['name'])                               ?   $value['name']      :   $value['id'],
                'DISABLED'  =>  isset($value['disabled'])   &&  $value['disabled']  ?   'disabled'          :   '',
                'SELECTED'  =>  $selected                       ?   'selected'          :   '',
            );

        }
        $data['LIST']           =   $this->list;

        /** @var \core\component\library\vendor\select2\component $select2 */
        $select2    =   library\component::connect('select2');
        $select2->setCss(self::$controller);
        $select2->setJS(self::$controller);
        $data['INIT']           =   $select2->returnInit($data);

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
        return false;
    }

    public function preUpdate()
    {
        $this->setFieldValue();
        return false;
    }


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
            if (isset($this->row[$this->configField['table']['field']])) {
                $where = Array(
                    $this->configField['table']['field_id'] => $this->row[$this->configField['table']['field']]
                );
                if ($this->multiple) {
                    $rows = parent::$db->selectRows($this->configField['table']['link'], $field, $where);
                    $id = Array();
                    foreach ($rows as $row) {
                        $id[] = $row['id'];
                    }
                    return $id;
                } else {
                    $row = parent::$db->selectRow($this->configField['table']['link'], $field, $where);
                    return isset($row['id']) ? $row['id'] : '';
                }
            }
            return $this->multiple  ?   Array() :   '';
        } else {
            if ($this->multiple) {
                return  explode(',', $this->configField['value']);
            } else {
                return isset($this->configField['value'])    ?   $this->configField['value'] :   '';
            }
        }
    }

    private function setFieldValue()
    {

        if (isset($this->configField['table'])) {
            $where = Array();
            $where[]    =   Array(
                'field'     =>  $this->configField['table']['field_id'],
                'value'     =>  $this->row[$this->configField['table']['field']]
            );

            parent::$db->dell($this->configField['table']['link'], $where);
            if ($this->multiple) {
                foreach ($this->row[$this->configField['table']['link']] as $table) {
                    $value = Array(
                        $this->configField['table']['field_id'] =>  $this->row[$this->configField['table']['field']],
                        $this->configField['table']['table_id'] =>  $table
                    );
                    parent::$db->inset($this->configField['table']['link'], $value);
                }
            } else {
                $value = Array(
                    $this->configField['table']['field_id'] =>  $this->row[$this->configField['table']['field']],
                    $this->configField['table']['table_id'] =>  $this->row[$this->configField['table']['link']]
                );
                parent::$db->inset($this->configField['table']['link'], $value);
            }
            $this->value = false;
        } else {
            if ($this->multiple) {
                return $this->value == null ? implode(',', $this->value) : '';
            } else {
                return $this->value == null ? $this->value : '';
            }
        }
    }
}