<?php
namespace core\component\CForm\field\AAsound;

use \core\component\{
    CForm,
    simpleView\simpleView
};

use core\component\resources\resources;

class component extends CForm\AField implements CForm\IField
{
    public function init()
    {
        resources::setJS(self::getTemplate('js/wavesurfer.min.js', __DIR__));
        resources::setJS(self::getTemplate('js/AAsound.js?v=2', __DIR__));
        resources::setCSS(self::getTemplate('css/AAsound.css?v=2', __DIR__));
        
        parent::init();
        $data['TD']                     =   '';
        $data['GRID']                   =   1;
        $data['PLACEHOLDER']            =   '';
        
        foreach ($this->configField as $key =>  $field)
        {
            $data[mb_strtoupper($key)] =  $field;
        }
        
        $data['VALUE']          =   $data['STORAGE'] . $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['READONLY']       =   $this->readonly     ?   'disabled'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
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
        if ($this->required && $this->value === '') {
            return true;
        } else {
            //$this->value = date('Y-m-d',strtotime($this->value));
            return false;
        }

    }



}