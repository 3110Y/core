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
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span class="uk-text-center">';
        $data['HREF_TWO']       =   $data['HREF'] == '<span class="uk-text-center">'   ?    '</span>'                    :   '</a>';
        $data['VALUE_NAME']     =   'Не выбрано';
        foreach ($list as $key => $value) {
            if (!isset($value['id'])) {
                $value['id'] = $key;
            }
            $this->list[$key] = Array(
                'ID'        =>  $value['id'],
                'NAME'      =>  isset($value['name'])                               ?   $value['name']      :   $value['id'],
                'DISABLED'  =>  isset($value['disabled'])   &&  $value['disabled']  ?   'disabled'          :   '',
                'SELECTED'  =>  $this->value == $value['id']                        ?   'selected'          :   '',
            );
            if ($this->value == $value['id']) {
                $data['VALUE_NAME'] =  $this->list[$key]['NAME'];
            }
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
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        return $this->required && $this->value === '';
    }
}