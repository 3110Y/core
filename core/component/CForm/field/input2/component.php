<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:48
 */

namespace core\component\CForm\field\file;

use \core\component\{
    CForm,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\CForm\field\UKInput
 */
class component extends CForm\AField implements CForm\IField
{


    public function init()
    {
        parent::init();
        $data['TD']                     =   '';
        $data['GRID']                   =   1;
        $data['PLACEHOLDER']            =   '';
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }

        $data['ATTRS'] = [];
        if ($this->required) {
            $this->attrs['required'] = null;
        }
        if ($this->readonly) {
            $this->attrs['disabled'] = null;
        }
        foreach($this->attrs as $key => $param) {
            if ($param === false) continue;
            $data['ATTRS'][] = $param != null ? "{$key}=\"{$param}\"" : $key;
        }
        $data['ATTRS']         =   implode(' ',$data['ATTRS']);
        $data['TYPE']           =   $this->inputType;
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
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
        return $this->required && $this->value === '';
    }



}