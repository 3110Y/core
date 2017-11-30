<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 17:39
 */

namespace core\component\CForm2\action\add;


use \core\component\{
	CForm2 as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\add
 */
class component extends CForm2\AAction implements CForm2\IAction
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.0;

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
	public function many()
	{
        $data   =   Array(
            'URL'   => self::$config['controller']::getPageURL(),
        );
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
		$answer =   simpleView\component::replace(self::getTemplate('tpl/rows.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}


}