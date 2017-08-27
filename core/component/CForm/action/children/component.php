<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 15.8.2017
 * Time: 15:38
 */

namespace core\component\CForm\action\children;

use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\children
 */
class component extends CForm\AAction implements CForm\IAction
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.1;

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
	public function one()
	{
		$application    =   self::$config['controller']::getApplication();
		$data   =   Array(
			'URL'           =>  $application['url'],
			'CHILDREN_URL'  =>  isset($this->componentSchema['children_url'])       ?   $this->componentSchema['children_url']  :   '',
			'ICON'          =>  isset($this->componentSchema['icon'])               ?   $this->componentSchema['icon']          :   '',
			'NAME'          =>  isset($this->componentSchema['name'])               ?   $this->componentSchema['name']          :   '',
		);
		foreach (self::$data as $key => $value) {
			$k = 'DATA_' . mb_strtoupper($key);
			$data[$k]   =   $value;
		}
		$answer =   simpleView\component::replace(self::getTemplate('tpl/row.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}
}