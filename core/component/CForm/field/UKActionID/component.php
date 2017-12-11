<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 16:16
 */

namespace core\component\CForm\field\UKActionID;

use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\UKActionID
 */
class component extends CForm\AField implements CForm\IField
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   2.0;


    /**
     * component constructor.
     * @param array $field
     * @param array $row
     */
    public function __construct($field, $row)
    {
        $this->row                      =   $row;
        $this->captionField['FIELD']    =   $this->configField;
        $this->captionField['TEXT']     =   $this->configField;
        self::$controller::setJS(self::getTemplate('js/actionID.js', __DIR__));
    }

    public function init()
    {
        $this->captionField['FIELD'] =   $this->configField;
        $this->template     =   self::getTemplate('template/template.tpl', __DIR__);
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['CAPTION_FIELD']  =   $this->captionField;
        $data['REQUIRED']       =   $this->required     ?   'required'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        foreach ($this->row as $key => $value) {
            $data['ROW_' . mb_strtoupper($key)] = $value;
        }
        $this->template         =   self::getTemplate('template/template.tpl', __DIR__);
        $this->answer           =   simpleView\component::replace($this->template, $data);
        $template               =   self::getTemplate('template/templateCaption.tpl', __DIR__);
        $this->captionField     =   Array(
            'FIELD' =>  Array(),
            'TEXT'  =>  simpleView\component::replace($template, $data)
        );
    }
}