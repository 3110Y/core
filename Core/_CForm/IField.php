<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:50
 */

namespace Core\_CForm;


/**
 * Interface IField
 * @package core\CForm
 */
interface IField
{
    /**
     * Инициализация
     */
    public function init();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getAnswer();

    /**
     * @return array
     */
    public function getField();

    /**
     * @return array
     */
    public function getValue();

    /**
     * @return boolean
     */
    public function preInsert();

    /**
     * @return boolean
     */
    public function postInsert();

    /**
     * @return boolean
     */
    public function preUpdate();

    /**
     * @return boolean
     */
    public function postUpdate();

    /**
     * @return bool
     */
    public function preDelete();

    /**
     * @return bool
     */
    public function postDelete();
}