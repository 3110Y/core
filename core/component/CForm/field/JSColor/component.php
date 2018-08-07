<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:48
 */

namespace core\component\CForm\field\JSColor;

use \core\component\{
    CForm,
    library as library,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\JSColor
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

        /** @var \core\component\library\vendor\JSColor\component $select2 */
        $select2 = library\component::connect('JSColor');
        $select2->setCss();
        $select2->setJS();

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