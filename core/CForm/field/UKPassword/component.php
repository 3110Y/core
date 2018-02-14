<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 23:53
 */

namespace core\CForm\field\UKPassword;

use \core\{
    CForm,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\CForm\field\UKPassword
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
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['READONLY']       =   $this->readonly     ?   'disabled'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
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
        $this->generationValue();
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        $this->generationValue();
        return $this->required && $this->value === '';
    }

    /**
     * Генерирует значение
     */
    public function generationValue()
    {
        if ($this->value !== '') {
            if (!isset($this->configField['algorithm'])) {
                $this->configField['algorithm'] = 'sha512';
            }
            $this->value    =   hash($this->configField['algorithm'], $this->value);
        } else {
            $this->value = false;
        }
    }
}