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
        'name'              =>  'core',
        'pass'              =>  'corecore',
        'character'         =>  'UTF8',
    );

    /**
     * router constructor.
     * @param string $URL URL приложения
     * @param array $application данные приложения
     */
    public function __construct($URL, $application)
    {
        self::$URL                  =  $URL;
        self::$application          =  $application;
        /** @var PDO\component $db */
        $db =   PDO\component::getInstance(self::$config);
        self::set('db', $db);
        self::set('view', new simpleView\component());
        self::get('view')->setExtension('tpl');
        self::$structure = $db->selectRows('admin_page','*', Array( 'status' => '1'), '`order_in_menu` ASC');
        if (empty(self::$structure)) {
            die('Нет страниц');
        }
    }



    /**
     * Запускает роутинг
     * @return router
     */
    public function run(): router
    {
        self::selectPage();
        $controllerBasic    =   new controllers\basic();
        if ($controllerBasic instanceof applicationWeb\IControllerBasic) {
            $controllerBasic->preInit();
        }
        $path               =   self::$application['path'];
        $controller         =   self::$page['controller'];
        $controller         = "application\\{$path}\\controllers\\{$controller}";
        $controller         = new $controller();
        if ($controller instanceof applicationWeb\IControllers) {
            $controller->init();
        }
        if ($controllerBasic instanceof applicationWeb\IControllerBasic) {
            $controllerBasic->postInit();
        }
        return $this;
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function render()
    {
        if (isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_REFERER'] !== '' &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return json_encode(self::$content);
        } else {
            $this->get('view')->setTemplate(self::$template);
            $this->get('view')->setData(self::$content);
            $this->get('view')->run();
            return $this->get('view')->get();
        }
    }
}
