<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 17:39
 */

namespace core\component\CForm\action\add;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\add
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
	 * генирирует для групповых действий
	 */
	public function rows()
	{
        $data   =   Array(
            'URL'   => '',
        );
		$answer =   simpleView\component::replace(self::getTemplate('tpl/rows.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}


}