<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 2:13
 */

namespace core\component\CForm\action\save;

use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 * @package core\component\CForm\action\save
 */
class component extends CForm\AAction implements CForm\IAction
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
     * генирирует для карточки
     */
    public function one()
    {
        $data   =   Array(
            'URL'   => self::$config['controller']::getPageURL(),
        );
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
	    self::$config['controller']::setJs(self::getTemplate('js/jquery.form.min.js', __DIR__));
	    self::$config['controller']::setJs(self::getTemplate('js/save.js', __DIR__));
        $answer =   simpleView\component::replace(self::getTemplate('tpl/item.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }

}
