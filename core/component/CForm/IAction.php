<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 13:20
 */

namespace core\component\CForm;


/**
 * Interface IAction
 *
 * @package core\component\CForm
 */
interface IAction
{
	/**
	 * Инициализация
	 */
	public function init();

	/**
	 * Запуск
	 */
	public function run();
}