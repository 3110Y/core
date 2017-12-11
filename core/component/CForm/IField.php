<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 09.12.2017
 * Time: 16:50
 */

namespace core\component\CForm;


/**
 * Interface IField
 * @package core\component\CForm
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
}