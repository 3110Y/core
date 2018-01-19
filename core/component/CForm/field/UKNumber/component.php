<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:48
 */

namespace core\component\CForm\field\UKNumber;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView,
    resources\resources
};


/**
 * Class component
 * @package core\component\CForm\field\UKNumber
 */
class component extends CForm\AField implements CForm\IField
{


    public function __construct($field)
    {
        parent::__construct($field);
        resources::setCss(self::getTemplate('css/input.css', __DIR__));
    }

    public function init()
    {
        parent::init();
        $data['TD']                   =   '';
        $data['GRID']                 =   1;
        $data['PLACEHOLDER']          =   '';
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<div class="uk-text-center">';
        $data['HREF_TWO']       =   $data['HREF'] == '<div class="uk-text-center">'   ?    '</div>'                    :   '</a>';
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