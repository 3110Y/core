<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\application;

use core\{
    config\config,
    registry\registry,
    authentication,
    PDO\PDO,
    router\route,
    simpleView\simpleView,
    URI\URL,
    URI\URI,
    router\router
};
use application\admin\controllers\system\common\basic;

/**
 * Class ARouter
 * @package core\application
 */
abstract class ARouter
{


    /**
     * @var \core\router\route
     */
    protected static $applicationRoute;

    /**
     * @var mixed configDB
     */
    protected $configDB = 'db';

    /**
     * @var mixed configDB
     */
    protected $configStructure = 'admin.structure';

    /**
     * @var mixed configDB
     */
    protected $redirectPage = 'enter';

    /**
     * @var
     */
    protected $controller;

    /**
     * @var string
     */
    protected $controllerBasic = basic::class;


    /**
     * router constructor.
     * @param route $route
     * @throws \Exception
     */
    public function __construct(route $route)
    {
        self::$applicationRoute     =   $route;
        application::setApplicationPointer(URL::getPointer());
        application::setApplicationURL(URL::getURLPointerNow());
        application::setTheme($route->getTheme());
        $path                 =   strrev($route->getController());
        $path                 =   strstr($path, '\\');
        if (false === $path) {
            throw new \RuntimeException('Немогу поянть пространство');
        }
        $path                 =   strrev($path);
        $path                 =   strtr($path , [
            '\\' =>  DIRECTORY_SEPARATOR
        ]);
        application::setPath($path);
        $config                 =   config::getConfig($this->configDB);
        /** @var PDO $db */
        $db =   PDO::getInstance($config);
        registry::set('db', $db);
        $auth = new authentication\component($db);
        registry::set('auth', $auth);
        registry::set('view', new simpleView());
        registry::get('view')->setExtension('tpl');
       // $this->auth();
        $this->run();
    }


    public function auth(): void
    {
        /** @var \core\authentication\component $auth */
        $auth = registry::get('auth');
        if (!$auth->get('authorization')->check()) {
            $auth->get('authorization')->logout();
            URI::redirect(self::$applicationURL);
        }
        $auth->get('objectRules')->register('application_' . self::$application['id'], 'Вход в приложение: ' . self::$applicationName);
        if (!$auth->get('rules')->check('application_' . self::$application['id']) && self::$URL[1] !== $this->redirectPage) {
            $auth->get('authorization')->logout();
            URI::redirect(self::$applicationURL . '/' . $this->redirectPage);
        }
    }


    public function run(): void
    {
        URL::plusPointer();
        if (!URI::isAjaxRequest()) {
            $this->controllerBasic::pre();
        } else {
            $this->controllerBasic::preAjax();
        }
        $scheme = config::getConfig($this->configStructure);
        $this->controller = (new router())->addStructure($scheme)->execute();
        if (!URI::isAjaxRequest()) {
            $this->controllerBasic::post();
        } else {
            $this->controllerBasic::postAjax();
        }
    }

    /**
     *  Запускает роутинг
     * @return string
     */
    public function render(): string
    {
        if (URI::isAjaxRequest()) {
            return json_encode(application::getData());
        }
        registry::get('view')->setTemplate($this->controller->getTemplate());
        registry::get('view')->setData(application::getData());
        registry::get('view')->run();
        return registry::get('view')->get();
    }



}