<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace application\admin;


use \core\component\{
    application\handler\Web as applicationWeb,
    database\driver\PDO as PDO,
    templateEngine\engine\simpleView as simpleView
};


/**
 * Class router
 * @package application\admin
 */
final class router extends applicationWeb\ARouter implements applicationWeb\IRouter
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
        self::$application          =  $application;
        self::set('db', PDO\component::getInstance(self::$config));
        self::set('view', new simpleView\component());
        self::get('view')->setExtension('tpl');
        self::$structure = Array(
            Array(
                'id'                =>  1,
                'parent_id'         =>  0,
                'name'              =>  'Главная',
                'url'               =>  '/',
                'meta_title'        =>  'Главная',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  'Главная Тест',
                'controller'        =>  'front',
                'template'          =>  'default',
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1
            ),
            Array(
                'id'                =>  13,
                'parent_id'         =>  0,
                'name'              =>  '404',
                'url'               =>  '404',
                'meta_title'        =>  '404',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  '404 Тест',
                'controller'        =>  'error',
                'template'          =>  'default',
                'home'              =>  0,
                'error'             =>  1,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  0,
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
        die($this->page);
        $controllerBasic    =   new controllers\basic();
        $controllerBasic->preInit();

        $controller         = new $this->page['controller']();
        $this->page['controller']::setPage($this->page);
        $this->page['controller']::setURL($this->URL);
        $this->page['controller']::setRouter($this);
        $controller->init();



        $this->template         =   $controller->getTemplate();

        $controllerBasic->postInit();
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
