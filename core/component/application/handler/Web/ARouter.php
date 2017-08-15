<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\component\application\handler\Web;


/**
 * Class ARouter
 * @package core\component\application\handler\Web
 */
abstract class ARouter extends AApplication
{


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
			$URLLeft = $URLCount - ($URLKey + 1);
			foreach (self::$structure as $item) {
				if (!isset($item['countSubURL'])) {
					/** @var \application\admin\controllers\basic $controller */
					$controller             =   $item['controller'];
					$controller             =   "application\\{$path}\\controllers\\{$controller}";
					$item['countSubURL']    =   $controller::$countSubURL;
				}
				if (
					$parentID === $item['parent_id']
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
						)
					)
				) {
					//нужная страница
					$url   =   Array();
					for ($i = 0, $iMax = $URLKey + 1; $i < $iMax; $i++) {
						$url[] = self::$URL[$i];
					}
					$controller::setPageURL(implode('/', $url));
					$subURL   =   Array();
					for ($i = $URLKey + 1; $i < $URLCount; $i++) {
						$subURL[] = self::$URL[$i];
					}
					$controller::setSubURL($subURL);
					return $item;
				} elseif (
					$parentID === $item['parent_id']
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