<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:13
 */

namespace Core\_CForm;


interface IAction
{

    /**
     * @param int $id
     * @return mixed
     */
    public function run($id = 0);

    /**
     *
     */
    public function init();

    /**
     * @return mixed
     */
    public function getAnswer();

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
}