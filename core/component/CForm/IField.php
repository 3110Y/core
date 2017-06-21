<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 13:38
 */

namespace core\component\CForm;

/**
 * Interface IField
 *
 * @package core\component\CForm
 */
interface IField
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