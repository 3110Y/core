<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:48
 */

namespace core\component\CForm\field\UKInput;

use \core\component\{
    CForm,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\UKInput
 */
class component extends CForm\AField implements CForm\IField
{

    protected $attrs = [];

    public function init()
    {
        parent::init();
        $data['TD']                     =   '';
        $data['GRID']                   =   1;
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }

        $data['ATTRS'] = '';
        if ($this->readonly) {
            $this->attrs[] = 'disabled';
        }
        foreach($this->attrs as $key => $param) {
            if ($param === false) {
                continue;
            }
            $data['ATTRS'] .= ' ' . (\is_int($key) ? $param : $key . '="' . $param . '"');
        }

        $data['TYPE']           =   $this->inputType;
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] === '<span>'  ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView::replace($this->template, $data);
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
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {

        return ($this->required && $this->value === '') || $this->uniqueTable();
    }

    public function uniqueTable()
    {
        if (isset($this->configField['uniqueTable'], $this->configField['uniqueMess'])) {
            $field  = $this->configField['field'];
            $table  = $this->configField['uniqueTable'];
            $where  =   [
                $field  =>    $this->value,
                [
                    'f' =>  'id',
                    'c' =>  '!=',
                    'v' =>  $this->row['id']
                ]
            ];
            $result =   parent::$db->selectCount($table,$field,$where) > 0;
            if ($result) {
                $this->errorMess = $this->configField['uniqueMess'];
            }
            return $result;
        }
        return false;
    }

    public function __construct($field, array $row = Array())
    {
        parent::__construct($field, $row);
        $this->attrs = $field['attrs'] ?? $this->attrs;
    }


}