<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace app\client;

use core\components\applicationWeb\connectors as applicationWebConnectors;
use core\core;


/**
 * Class router
 * Роутер приложения
 * @package app
 */
final class router extends applicationWebConnectors\ARouter implements applicationWebConnectors\IRouter
{

    private static $config = Array(
        'driver'            =>  'mysql',
        'host'              =>  '127.0.0.1',
        'port'              =>  '3306',
        'db'                =>  'core',
        'name'              =>  'root',
        'pass'              =>  '',
        'character'         =>  'UTF8',
    );

    /**
     * router constructor.
     * @param string $URL URL приложения
     * @param array $application данные приложения
     */
    public function __construct($URL, $application)
    {
        $this->URL                  =  $URL;
        $this->application          =  $application;
        $this->set('view', core::getComponents('simpleView'));
        $this->get('view')->setExtension('tpl');
        $this->set('db', core::getComponents('PDO',true)::getInstance(self::$config));
        $this->set('GF', core::getComponents('generatorForm',true));
        $this->structure = Array(
            Array(
                'id'                =>  1,
                'parent_id'         =>  0,
                'name'              =>  'Главная',
                'url'               =>  '/',
                'meta_title'        =>  'Главная',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  'Главная Тест',
                'controller'        =>  controllers\front::class,
                'template'          =>  'front',
                'home'              =>  1,
                'error'             =>  0
            ),
            Array(
                'id'                =>  2,
                'parent_id'         =>  0,
                'name'              =>  '404',
                'url'               =>  '404',
                'meta_title'        =>  '404',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  '404 Тест',
                'controller'        =>  controllers\error::class,
                'template'          =>  '404',
                'home'              =>  0,
                'error'             =>  1
            ),
            Array(
                'id'                =>  3,
                'parent_id'         =>  0,
                'name'              =>  '3',
                'url'               =>  '3',
                'meta_title'        =>  '3',
                'meta_keywords'     =>  '3, Тест',
                'meta_description'  =>  '3',
                'controller'        =>  controllers\basic::class,
                'template'          =>  'basic',
                'home'              =>  1,
                'error'             =>  0
            ),
            Array(
                'id'                =>  4,
                'parent_id'         =>  3,
                'name'              =>  '4',
                'url'               =>  '4',
                'meta_title'        =>  '4',
                'meta_keywords'     =>  '4, Тест',
                'meta_description'  =>  '4',
                'controller'        =>  controllers\basic::class,
                'template'          =>  'basic',
                'home'              =>  1,
                'error'             =>  0
            ),
        );

    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function run()
    {
        $this->selectPage();
        /** @property  \app\client\controllers\front $controller */
        $controller         = new $this->page['controller']();
        $this->page['controller']::setPage($this->page);
        $this->page['controller']::setURL($this->URL);
        $this->page['controller']::setRouter($this);
        $controller->init();
        $this->content      = $controller->getContent();
        $this->template     = $controller->getTemplate();
        return $this;
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function render()
    {

        $this->get('view')->setTemplate($this->template);
        $this->get('view')->setData($this->content);
        $this->get('view')->run();
        return  $this->get('view')->get();
    }
}
