<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\component\application;

use core\component\{
    config\config, registry\registry, authentication, PDO\PDO, simpleView\simpleView
};

/**
 * Class ARouter
 * @package core\component\application
 */
abstract class ARouter extends AApplication
{
    /**
     * @var mixed table
     */
    protected $table = 'admin_page';

    /**
     * @var mixed fields
     */
    protected $fields = '*';

    /**
     * @var mixed where
     */
    protected $where = Array(
        'status' => '1'
    );

    /**
     * @var mixed order
     */
    protected $order = '`order_in_menu` ASC';

    /**
     * @var mixed configDB
     */
    protected $configDB = 'db.common';

    /**
     * @var mixed configDB
     */
    protected $redirectPage = 'enter';

    /**
     * @var AControllers шаблон
     */
    protected $controller;

    /**
     * router constructor.
     *
     * @param array  $URL           URL приложения
     * @param array  $application   данные приложения
     * @param bool   $isAjaxRequest AJAX запрос
     */
    public function __construct($URL, $application, $isAjaxRequest = false)
    {
        self::$isAjaxRequest        =  $isAjaxRequest;
        self::$URL                  =  $URL;
        self::$application          =  $application;
        $config                     =   config::getConfig($this->configDB);
        /** @var PDO $db */
        $db =   PDO::getInstance($config);
        registry::set('db', $db);
        $auth = new authentication\component($db);
        registry::set('auth', $auth);
        registry::set('view', new simpleView());
        $view = registry::get('view');
        $view->setExtension('tpl');
        self::$structure = $db->selectRows($this->table,$this->fields, $this->where, $this->order);

        if (empty(self::$structure)) {
            die('Нет страниц');
        }
    }


    /**
     * @return $this
     */
    public function run(): self
    {
        /** @var \core\component\authentication\component $auth */
        $auth = registry::get('auth');
        if (!$auth->get('authorization')->check()) {
            $auth->get('authorization')->logout();
            self::redirect(self::$application['url']);
        }
        $auth->get('objectRules')->register('application_' . self::$application['id'], 'Вход в приложение: ' . self::$application['name']);
        if ((!isset(self::$URL[1]) || $this->redirectPage !== self::$URL[1]) && !$auth->get('rules')->check('application_' . self::$application['id'])) {
            # Нужна авторизация и путь не совпадает со страницей авторизации
            $auth->get('authorization')->logout();
            $url = (self::$application['url'] === '/' ? '' : self::$application['url'] ) . '/' . $this->redirectPage;
            self::redirect($url);
        }
        self::selectPage();
        $path               =   self::$application['path'];
        /** @var IControllerBasic $controllerBasic */
        $controllerBasic    =   "application\\{$path}\\controllers\\" . self::$application['basicController'];
        $controllerBasic    =   new $controllerBasic();
        $issetBasic         =   $controllerBasic instanceof IControllerBasic;
        if ($issetBasic) {
            if (!self::$isAjaxRequest) {
                $controllerBasic->pre();
            } else {
                $controllerBasic->preAjax();
            }
        }
        $controller         =   self::$page['controller'];
        $this->controller         = "application\\{$path}\\controllers\\{$controller}";
        /** @var \application\admin\controllers\page controller */
        $this->controller         = new $this->controller();
        if ($issetBasic) {
            if (!self::$isAjaxRequest) {
                $controllerBasic->post();
            } else {
                $controllerBasic->postAjax();
            }
        }

        return $this;
    }

    /**
     *  Запускает роутинг
     * @return string
     */
    public function render(): string
    {
        if (self::$isAjaxRequest ) {
            return json_encode(self::$content);
        }
        $view = registry::get('view');
        $view->setTemplate(self::getTemplate($this->controller->template));
        $view->setData(self::$content);
        $view->run();
        return $view->get();
    }


    /**
     * Задает текущую страницу и страницу Ошибок
     */
    protected static function selectPage(): void
    {
        self::$pageError    = self::searchPageError();
        self::$page         = self::searchPage();
    }

    /**
     * Отдает страницу Ошибок
     * @return array
     */
    public static function searchPageError(): array
    {
        foreach (self::$structure as $item) {
            if ($item['error']) {
                return $item;
            }
        }
        return self::$structure[0];
    }

    /**
     * Отдает текущую
     *
     * @return array текущая страница
     */
    private static function searchPage(): array
    {
        $parentID   = 0;
        $URLCount   = \count(self::$URL) - 1;
        $path           =   self::$application['path'];
        /** @var \core\component\authentication\component $auth */
        $auth = registry::get('auth');
        $auth->get('authorization')->check();
        foreach (self::$URL as $URLKey => $URLItem) {
            if ($URLKey === 0) {
                continue;
            }
            $URLLeft = $URLCount - ($URLKey + 1);
            foreach (self::$structure as $item) {
                $auth->get('objectRules')->register(
                    'application_' . self::$application['id'] . '_page_' . $item['id'],
                    'Приложение: ' . self::$application['name']. " Отображать пункт меню: {$item['name']}"
                );
                if (!isset($item['countSubURL'])) {
                    /** @var AControllers $controller */
                    $controller                 =   $item['controller'];
                    $controller                 =   "application\\{$path}\\controllers\\{$controller}";
                    $item['controllerObject']   =   $controller;
                    $item['countSubURL']        =   $controller::$countSubURL;
                }
                if (
                    $parentID === (int)$item['parent_id']
                    && (
                        $URLCount === $URLKey
                        || (
                            $item['countSubURL'] === false
                            || $item['countSubURL'] >= $URLCount - $URLLeft
                        )
                    )
                    && (
                        $item['url'] === $URLItem
                        || (
                            $item['url'] === '/'
                            && $URLItem === ''
                            && (
                                $item['countSubURL'] === false
                                || $item['countSubURL'] >= $URLCount + $URLLeft
                            )
                        )
                    )
                ) {
                    //нужная страница
                    $url   =   Array();
                    /** @noinspection ForeachInvariantsInspection */
                    for ($i = 0, $iMax = $URLKey + 1; $i < $iMax; $i++) {
                        $url[] = self::$URL[$i];
                    }
                    $item['controllerObject']::setPageURL(implode('/', $url));
                    $subURL   =   Array();
                    for ($i = $URLKey + 1; $i <= $URLCount; $i++) {
                        $subURL[] = self::$URL[$i];
                    }
                    $item['controllerObject']::setSubURL($subURL);


                    if (!$auth->get('rules')->check('application_' . self::$application['id'] . '_page_' . $item['id'])) {
                        return self::$pageError;
                    }
                    return $item;
                }

                if (
                    $parentID === (int)$item['parent_id']
                    && (
                        $item['url'] === $URLItem
                        || (
                            $item['url'] === '/'
                            && $URLItem === ''
                        )
                    ) && $auth->get('rules')->check('application_' . self::$application['id'] . '_page_' . $item['id'])
                ) {
                    $parentID = (int) $item['id'];
                }
            }
        }
        return self::$pageError;
    }



}