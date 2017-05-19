<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 14:27
 */

namespace core\component\CForm\action\edit;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\edit
 */
class component extends CForm\AAction implements CForm\IAction
{
	public function init()
	{
		// TODO: Implement init() method.
	}

	public function run()
	{
		// TODO: Implement run() method.
	}
	/**
	 * генирирует для листинга
	 */
	public function row()
	{
		$answer =   simpleView\component::replace(self::getTemplate('tpl/row.tpl', __DIR__));
		$this->setComponentAnswer($answer);
	}
}