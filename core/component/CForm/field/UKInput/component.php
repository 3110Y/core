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
    templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\UKInput
 */
class component extends CForm\AField implements CForm\IField
{

    public function __construct($field)
    {
        parent::__construct($field);
        self::$controller::setCss(self::getTemplate('css/input.css', __DIR__));
    }
    /**
     * @var string
     */
    protected $value = '';

    public function init()
    {
        parent::init();
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
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView\component::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView\component::replace($this->template, $data);
    }

    public function view()
    {
        $this->captionField['FIELD'] =   $this->configField;
        $this->template     =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function edit()
    {
        $this->captionField['FIELD'] =   $this->configField;
        $this->template     =   self::getTemplate('template/view.tpl', __DIR__);

    }


}