<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:13
 */

namespace core\component\CForm;


class AAction extends ACForm
{
    /**
     * @var array
     */
    protected $fields = Array();

    /**
     * @var array
     */
    protected $data = Array();

    /**
     * @var array
     */
    protected $answer = Array();

    /**
     * @var bool
     */
    protected $isError = false;

    /**
     * AAction constructor.
     * @param array $fields
     * @param array $data
     */
    public function __construct($fields = Array(), $data = Array())
    {
        $this->fields = $fields;
        $this->data = $data;
    }

    public function init()
    {

    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param string $method
     */
    protected function preMethod($method)
    {
        foreach ($this->fields  as $key => $field) {

            if (!isset($field['field']) && is_string($key)) {
                $field['field'] = $key;
            }
            if (isset($field['field'], $this->data[$field['field']])) {
                $field['value'] = $this->data[$field['field']];
            }
            if (!isset($field['mode'])) {
                $field['mode'] = 'edit';
            }
            if (isset($field['type'])) {
                $fieldName = $field['type'];
                $fieldObject = "core\component\CForm\\field\\{$fieldName}\component";
                if (class_exists($fieldObject)) {
                    /** @var \core\component\CForm\field\UKInput\component $fieldComponent */
                    $fieldComponent = new $fieldObject($field, $this->data);
                    $fieldComponent->init();
                    $this->answer[$key] =   $fieldComponent->$method();
                    if (isset($field['field']) &&  $this->answer[$key]) {
                        $this->answer['errorData'][$field['field']] = true;
                    }
                    if (isset($field['field'], $this->data[$field['field']])) {
                        $this->data[$field['field']] = $fieldComponent->getValue();
                    }
                    if ($this->answer[$key]) {
                        $this->isError = true;
                    }
                }
            }
        }
    }

    /**
     * @param string $method
     */
    protected function postMethod($method)
    {
        foreach (self::$viewerConfig['field']  as $key => $field) {
            if (!isset($field['field']) && is_string($key)) {
                $field['field'] = $key;
            }
            if (isset($field['field'], $this->data[$field['field']])) {
                $field['value'] = $this->data[$field['field']];
            }
            if (!isset($field['mode'])) {
                $field['mode'] = 'edit';
            }
            if (isset($field['type'])) {
                $fieldName = $field['type'];
                $fieldObject = "core\component\CForm\\field\\{$fieldName}\component";
                if (class_exists($fieldObject)) {
                    /** @var \core\component\CForm\field\UKInput\component $fieldComponent */
                    $fieldComponent = new $fieldObject($field, $this->data);
                    $fieldComponent->init();
                    $fieldComponent->$method();
                }
            }
        }
    }


    public function preInsert()
    {
        return true;
    }

    public function postInsert()
    {
        return true;
    }

    public function preUpdate()
    {
        return true;
    }

    public function postUpdate()
    {
        return true;
    }

    public function preDell()
    {
        return true;
    }

    public function postDell()
    {
        return true;
    }

}