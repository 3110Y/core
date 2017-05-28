<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 13:19
 */

namespace core\component\CForm\action\dell;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\action\dell
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

	/**
	 * генирирует для листинга
	 */
	public function row()
	{
        $data   =   Array(
            'URL'           => self::$config['url'],
            'URL_BACK'      => base64_encode(self::$config['url']),
        );
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
		$answer =   simpleView\component::replace(self::getTemplate('tpl/row.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}

}