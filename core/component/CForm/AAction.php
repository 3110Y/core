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
                    if (isset($field['field']) && $this->answer[$key] === true) {
                        $this->answer['errorData'][$field['field']] = true;
                        $errorMess    =   $fieldComponent->getErrorMess();
                        if ($errorMess !== '') {
                            $this->answer['errorData'][$field['field']]  = $errorMess;
                        }
                    }
                    if (isset($field['field'], $field['define']) || isset($field['field'], $this->data[$field['field']])) {
                        $this->data[$field['field']] = $fieldComponent->getValue();
                        $_POST[$field['field']]         =   $this->data[$field['field']];
                        if ($this->data[$field['field']] === false) {
                            unset($this->data[$field['field']]);
                        }
                    }
                    if (isset($field['table']['link'], $this->data[$field['table']['link']])) {
                        $this->data[$field['table']['link']] = $fieldComponent->getValue();
                        $_POST[$field['table']['link']]      = $this->data[$field['table']['link']];

                        if ($this->data[$field['table']['link']] === false) {
                            unset($this->data[$field['table']['link']]);
                        }
                    }
                    if ($this->answer[$key] === true) {
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
        return false;
    }

    public function postInsert()
    {
        return false;
    }

    public function preUpdate()
    {
        return false;
    }

    public function postUpdate()
    {
        return false;
    }

    public function preDell()
    {
        return false;
    }

    public function postDell()
    {
        return false;
    }

}