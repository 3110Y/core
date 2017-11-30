<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 13:19
 */

namespace core\component\CForm2\action\dell;


use \core\component\{
	CForm2 as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\dell
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
		$urlBack = self::$config['controller']::getPageURL();
		if (!empty(self::$subURL)) {
			$urlBack .= '/' . implode('/', self::$subURL);
		}
        $data   =   Array(
            'URL'   => self::$config['controller']::getPageURL(),
	        'URL_BACK'      => base64_encode($urlBack),
        );
		self::$config['controller']::setJs(self::getTemplate('js/dell.js', __DIR__));
		$answer =   simpleView\component::replace(self::getTemplate('tpl/rows.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}

	/**
	 * генирирует для листинга
	 */
	public function one()
	{
		$urlBack = self::$config['controller']::getPageURL();
		if (!empty(self::$subURL)) {
			$urlBack .= '/' . implode('/', self::$subURL);
		}
        $data   =   Array(
            'URL'           => self::$config['controller']::getPageURL(),
            'URL_BACK'      => base64_encode($urlBack),
        );
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
		$answer =   simpleView\component::replace(self::getTemplate('tpl/row.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}

}