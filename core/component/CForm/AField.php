<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:50
 */

namespace core\component\CForm;


class AField extends ACForm
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string режим
     */
    protected $modeField = '';

    /**
     * @var array режим
     */
    protected $captionField = Array(
        'FIELD'     =>  '',
        'TEXT'      =>  ''
    );

    /**
     * @var array
     */
    protected $configField = Array();

    /**
     * @var string
     */
    protected $answer = '';

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var string
     */
    protected $style = '';

    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var string
     */
    protected $idField = '';

    /**
     * @var int
     */
    protected static $iterator = 0;

    /**
     * AField constructor.
     * @param $field
     */
    public function __construct($field)
    {
        self::$iterator++;
        $this->value        =   $field['value']         ?? $this->value;
        $this->modeField    =   $field['mode']          ?? $this->modeField;
        $this->required     =   $field['required']      ?? $this->required;
        $this->idField      =   $field['field']         ?? 'field_' . __CLASS__ . '_' .  self::$iterator . '_' .  uniqid();
        if (isset($field['class'])) {
           if (!is_array($field['class'])) {
               $this->class        =   $field['class'];
           } else {
               $this->class        =   implode(' ', $field['class']);
           }
        }
        if (isset($field['style'])) {
            $style  =   '';
            foreach ($field['style'] as $nsme => $value) {
                $style .= "{$nsme}: {$value}";
            }
            $this->style = " style='{$style}' ";
        }

        if (isset($field['caption'])) {
            $this->captionField['TEXT'] = $field['caption'];
        }
        unset($field['value'], $field['mode'], $field['caption'], $field['required'], $field['style']);
        $this->configField              =   $field;
        $this->captionField['FIELD']    =   $this->configField;
    }

    /**
     * Инициализация
     */
    public function init()
    {
        $this->{$this->modeField}();
    }

    /**
     * @return array
     */
    public function getCaption()
    {
        return $this->captionField;
    }

    /**
     * @return mixed|string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @return array
     */
    public function getField()
    {
        $this->configField['value']     =   $this->value;
        $this->configField['mode']      =   $this->modeField;
        $this->configField['caption']   =   $this->captionField;
        return $this->configField;
    }

    public static function preInsert()
    {

    }

    public static function preInsertAll()
    {

    }

    public static function preUpdate()
    {

    }

    public static function preUpdateAll()
    {

    }


}