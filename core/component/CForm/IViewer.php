<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:46
 */

namespace core\component\CForm;


/**
 * Interface IViewer
 *
 * @package core\component\CForm
 */
interface IViewer
{
	/**
	 * Инициализация
	 */
	public function init();

	/**
	 * Запуск
	 */
	public function run();

    /**
     * @return mixed
     */
    public function getAnswer();
}