<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 16:16
 */

namespace core\CForm\field\UKActionID;

use \core\{
    CForm as CForm,
    simpleView\simpleView,
    resources\resources
};


/**
 * Class component
 * @package core\CForm\field\UKActionID
 */
class component extends CForm\AField implements CForm\IField
{


    /**
     * component constructor.
     * @param array $field
     * @param array $row
     */
    public function __construct($field, $row)
    {
        $this->idField                  =   $field['field']         ?? 'field_' . __CLASS__ . '_' .  self::$iterator . '_' .  uniqid();
        $this->row                      =   $row;
        $this->labelField['FIELD']      =   $this->idField;
        $this->labelField['TEXT']       =   $this->configField;
        resources::setJS(self::getTemplate('js/actionID.js', __DIR__));
    }

    public function init()
    {
        $data['TD']                    =   'uk-table-shrink';
        $this->labelField['FIELD']      =   $this->configField;
        $this->template     =   self::getTemplate('template/template.tpl', __DIR__);
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   'required'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        foreach ($this->row as $key => $value) {
            $data['ROW_' . mb_strtoupper($key)] = $value;
        }
        $this->template         =   self::getTemplate('template/template.tpl', __DIR__);
        $this->answer           =   simpleView::replace($this->template, $data);
        $template               =   self::getTemplate('template/templateCaption.tpl', __DIR__);
        $this->labelField     =   Array(
            'FIELD' =>  Array(),
            'TEXT'  =>  simpleView::replace($template, $data)
        );
    }
}