<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\component\application;

use core\core;
use \core\component\{
    registry\registry as registry,
    authentication as authentication,
    database\driver\PDO as PDO,
    templateEngine\engine\simpleView as simpleView
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
    protected static $table = 'admin_page';

    /**
     * @var mixed fields
     */
    protected static $fields = '*';

    /**
     * @var mixed where
     */
    protected static $where = Array(
        'status' => '1'
    );

    /**
     * @var mixed order
     */
    protected static $order = '`order_in_menu` ASC';

    /**
     * @var mixed configDB
     */
    protected static $configDB = 'db.common';

    /**
     * @var mixed configDB
     */
    protected static $redirectPage = 'enter';

    /**
     * @var object шаблон
     */
    protected static $controller = null;

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
        $config                     =   core::getConfig(self::$configDB);
        /** @var PDO\component $db */
        $db =   PDO\component::getInstance($config);
        registry::set('db', $db);
        $auth = new authentication\component($db);
        registry::set('auth', $auth);
        registry::set('view', new simpleView\component());
        registry::get('view')->setExtension('tpl');
        self::$structure = $db->selectRows(self::$table,self::$fields, self::$where, self::$order);

        if (empty(self::$structure)) {
            die('Нет страниц');
        }
    }


    /**
     * @return $this
     */
    public function run()
    {
        /** @var \core\component\authentication\component $auth */
        $auth = registry::get('auth');
        $auth->get('authorization')->check();
        $auth->get('object')->register('application_' . self::$application['id'], 'Вход в приложение: ' . self::$application['name']);
        if (!$auth->get('rules')->check('application_' . self::$application['id']) && self::$URL[1] !== self::$redirectPage) {
            $auth->get('authorization')->logout();
            self::redirect(self::$application['url'] . '/' . self::$redirectPage);
        }
        self::selectPage();
        $controllerBasic    =   'application\\' . self::$application['path'] . '\controllers\\' . self::$application['basicController'];
        $controllerBasic    =   new $controllerBasic();
        if ($controllerBasic instanceof IControllerBasic) {
            $controllerBasic->preInit();
        }
        $path               =   self::$application['path'];
        $controller         =   self::$page['controller'];
        self::$controller         = "application\\{$path}\\controllers\\{$controller}";
        /** @var \application\admin\controllers\page controller */
        self::$controller         = new self::$controller();
        if (self::$controller  instanceof IControllers) {
            self::$controller->init();
        }
        if (!self::$isAjaxRequest && $controllerBasic instanceof IControllerBasic) {
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
        registry::get('view')->setTemplate(self::getTemplate(self::$controller->template));
        registry::get('view')->setData(self::$content);
        registry::get('view')->run();
        return registry::get('view')->get();
    }


    /**
     * Задает текущую страницу и страницу Ошибок
     */
    protected static function selectPage()
    {
        self::$pageError    = self::searchPageError();
        self::$page         = self::searchPage();
    }

    /**
     * Отдает страницу Ошибок
     * @return array
     */
    public static function searchPageError()
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
		$URLCount   = count(self::$URL) - 1;
		$path           =   self::$application['path'];
		foreach (self::$URL as $URLKey => $URLItem) {
			if ($URLKey === 0) {
				continue;
			}
			$URLLeft = $URLCount - ($URLKey + 1);
			foreach (self::$structure as $item) {
				if (!isset($item['countSubURL'])) {
					/** @var \application\admin\controllers\basic $controller */
					$controller                 =   $item['controller'];
					$controller                 =   "application\\{$path}\\controllers\\{$controller}";
					$item['controllerObject']   =   $controller;
					$item['countSubURL']        =   $controller::$countSubURL;
				}
				if (
					(int)$parentID === (int)$item['parent_id']
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
					for ($i = 0, $iMax = $URLKey + 1; $i < $iMax; $i++) {
						$url[] = self::$URL[$i];
					}
					$item['controllerObject']::setPageURL(implode('/', $url));
					$subURL   =   Array();
					for ($i = $URLKey + 1; $i <= $URLCount; $i++) {
						$subURL[] = self::$URL[$i];
					}
					$item['controllerObject']::setSubURL($subURL);
					return $item;
				} elseif (
					(int)$parentID === (int)$item['parent_id']
					&& (
						$item['url'] === $URLItem
						|| (
							$item['url'] === '/'
							&& $URLItem === ''
						)
					)
				) {
					//ищем подстраницу
					$parentID = $item['id'];
				}
			}
		}
		return self::$pageError;
	}



}