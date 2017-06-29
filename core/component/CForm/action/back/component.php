<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 1:53
 */

namespace core\component\CForm\action\back;

use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};

/**
 * Class component
 * @package core\component\CForm\action\back
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
        if (!empty($_GET['back'])) {
            $urlBack = base64_decode($_GET['back']);
        } else {
            $urlBack = self::$config['controller']::getPageURL();
        }
        $data['URL']   = $urlBack;
        foreach (self::$data as $key => $value) {
            $k = 'DATA_' . mb_strtoupper($key);
            $data[$k]   =   $value;
        }
        $answer =   simpleView\component::replace(self::getTemplate('tpl/item.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }
}