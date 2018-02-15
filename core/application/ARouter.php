<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\application;

use core\{
    config\config, registry\registry, authentication, PDO\PDO, router\route, simpleView\simpleView, router\URL, router\router
};
use application\admin\controllers\system\common\basic;

/**
 * Class ARouter
 * @package core\application
 */
abstract class ARouter extends AApplication
{

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


    protected $controller;

    /**
     * @var string
     */
    protected $controllerBasic = basic::class;

    /**
     * @var string
     */
    protected $themeCurrent = 'admin\theme\basic';

    /**
     * @var string
     */
    protected $applicationNameCurrent = 'панель';


    /**
     * router constructor.
     * @param route $route
     */
    public function __construct(route $route)
    {
        var_dump($route);
        die();
        $config                 =   config::getConfig($this->configDB);
        self::$theme            = $this->themeCurrent;
        self::$applicationName  = $this->applicationNameCurrent;
        self::$applicationURL   = URL::getURLPointerNow();
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
            self::redirect(self::$applicationURL);
        }
        $auth->get('object')->register('application_' . self::$application['id'], 'Вход в приложение: ' . self::$applicationName);
        if (!$auth->get('rules')->check('application_' . self::$application['id']) && self::$URL[1] !== $this->redirectPage) {
            $auth->get('authorization')->logout();
            self::redirect(self::$applicationURL . '/' . $this->redirectPage);
        }
    }


    public function run(): void
    {
        $controllerBasic    =   $this->controllerBasic;
        $this->controllerBasic    =   new $controllerBasic();
        $issetBasic         =   $controllerBasic instanceof IControllerBasic;
        if ($issetBasic) {
            if (!self::isAjaxRequest()) {
                $this->controllerBasic->pre();
            } else {
                $this->controllerBasic->preAjax();
            }
        }
        URL::plusPointer();
        $scheme = config::getConfig($this->configStructure);
        $this->controller = (new router())->addStructure($scheme)->execute();
        var_dump($this->controller);
        if ($issetBasic) {
            if (!self::isAjaxRequest()) {
                $this->controllerBasic->post();
            } else {
                $this->controllerBasic->postAjax();
            }
        }
    }

    /**
     *  Запускает роутинг
     * @return string
     */
    public function render(): string
    {
        if (self::isAjaxRequest()) {
            return json_encode(self::$content);
        }
        registry::get('view')->setTemplate(self::getTemplate($this->controller->template));
        registry::get('view')->setData(self::$content);
        registry::get('view')->run();
        return registry::get('view')->get();
    }



}