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
    protected $labelField = Array(
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
     * @var array
     */
    protected $row = Array();

    /**
     * AField constructor.
     * @param $field
     * @param array $row
     */
    public function __construct($field, $row = Array())
    {
        self::$iterator++;
        $this->row          =   $row;
        $this->value        =   $field['value']         ?? $this->value;
        $this->modeField    =   $field['mode']          ?? $this->modeField;
        $this->required     =   $field['required']      ?? $this->required;
        $this->idField      =   $field['field']         ?? $field['table']['link']  ??  'field_' . __CLASS__ . '_' .  self::$iterator . '_' .  uniqid();
        if (isset($field['class'])) {
           if (!is_array($field['class'])) {
               $this->class        =   $field['class'];
           } else {
               $this->class        =   implode(' ', $field['class']);
           }
        }
        if (isset($field['style'])) {
            $style  =   '';
            foreach ($field['style'] as $name => $value) {
                $style .= "{$name}: {$value}";
            }
            $this->style = " style='{$style}' ";
        }

        if (isset($field['label'])) {
            $this->labelField['TEXT'] = $field['label'];
        }

        unset($field['mode'], $field['label'], $field['required'], $field['class'], $field['style']);
        $this->configField              =   $field;
        $this->labelField['FIELD']      =   $this->idField;
    }

    /**
     * Инициализация
     */
    public function init()
    {
        if ($this->modeField != '') {
            $this->{$this->modeField}();
        }
    }

    /**
     * @return array
     */
    public function getLabel()
    {
        return $this->labelField;
    }

    /**
     * @return mixed|string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @return mixed|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getField()
    {
        $this->configField['id']        =   $this->idField;
        $this->configField['value']     =   $this->value;
        $this->configField['mode']      =   $this->modeField;
        $this->configField['class']     =   $this->class;
        $this->configField['label']     =   $this->labelField;
        return $this->configField;
    }

    /**
     * @return bool
     */
    public function preInsert()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function postInsert()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function preUpdate()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function postUpdate()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function preDelete()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function postDelete()
    {
        return false;
    }


}