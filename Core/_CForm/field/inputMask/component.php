<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 19.03.18
 * Time: 19:28
 */

namespace Core\_CForm\field\inputMask;

use Core\{
    _CForm\AField, _CForm\IField, _resources\resources, _simpleView\simpleView
};


class component extends AField implements IField
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
        $data['MASK']           =   $data['MASK']       ??   'phone';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView::replace($this->template, $data);
        resources::setCss(self::getTemplate('vendor/Inputmask/css/inputmask.css', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/inputmask.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/inputmask.extensions.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/inputmask.numeric.extensions.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/inputmask.date.extensions.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/inputmask.phone.extensions.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/jquery.inputmask.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/phone-codes/phone.js', __DIR__));
        resources::setJs(self::getTemplate('vendor/Inputmask/dist/inputmask/phone-codes/phone-ru.js', __DIR__));
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
        $this->generationValue();
        return $this->required && $this->value === '';
    }


    /**
     * Генерирует значение
     */
    public function generationValue()
    {
        if ($this->value !== '' && isset($this->configField['mask']) && $this->configField['mask'] === 'phone') {
            $this->value = strtr($this->value, [
                '(' => '',
                ')' => '',
                '-' => '',
                '-' => '',
                '+' => '',
                ' ' => '',
            ]);
        }
    }

}