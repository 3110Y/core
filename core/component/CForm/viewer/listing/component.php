<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:38
 */

namespace core\component\CForm\viewer\listing;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\viewer\listing
 */
class component extends CForm\AViewer implements CForm\IViewer
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   2;


    /**
     * Инициализация
     */
    public function init()
    {
        var_dump($this->answer);
        // TODO: Implement init() method.
    }

    /**
     * Запуск
     */
    public function run()
    {
        // TODO: Implement run() method.
    }

}