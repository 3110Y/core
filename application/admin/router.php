<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace application\admin;


use \core\component\{
    rules as rules,
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
	 *
	 * @param string $URL           URL приложения
	 * @param array  $application   данные приложения
	 * @param bool   $isAjaxRequest AJAX запрос
	 */
    public function __construct($URL, $application, $isAjaxRequest = false)
    {
        self::$isAjaxRequest        =  $isAjaxRequest;
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
        $URL = implode('/', self::$URL);
        $check = (new rules\component($URL))->setDB(self::get('db'))->setKey(self::$application['name'])
            ->setAuthorizationURL(self::$application['url'] . '/enter')
            ->setAuthorizationNoPage(self::$application['url'] . '/404')->check();
        if ($check !== true) {
            self::redirect($check);
        }
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
        if (!self::$isAjaxRequest && $controllerBasic instanceof applicationWeb\IControllerBasic) {
            $controllerBasic->postInit();
        }
        return $this;
    }

    /**
     *  Запускает роутинг
     * @return string
     */
    public function render()
    {
        if (self::$isAjaxRequest ) {
            return json_encode(self::$content);
        }
        self::get('view')->setTemplate(self::$template);
        self::get('view')->setData(self::$content);
        self::get('view')->run();
        return self::get('view')->get();
    }
}
