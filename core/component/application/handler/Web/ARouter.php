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
     * Отдает текущую Ошибок
     *
     * @param int    $parentID  уровень страницы
     * @param string $parentURL родительский URL
     *
     * @return array текущая страница
     */
    private static function searchPage($parentID = 0, $parentURL ='')
    {
        //TODO: Переделать, не работают подуровни
        foreach (self::$structure as $item) {
            if (
                $item['parent_id'] == $parentID
                && (
                    $item['url'] === self::$URL[$parentID + 1]
                    || (
                        $item['url'] === '/'
                        && self::$URL[$parentID + 1] === ''
                    )
                )
            ) {
                $path           =   self::$application['path'];
                $controller     =   $item['controller'];
                /** @var \application\admin\controllers\basic $controller */
                $controller     =   "application\\{$path}\\controllers\\{$controller}";
                $countSubURL    =   $controller::$countSubURL;
	            $item['url'] = ($item['url'] === '/' && $parentID == 0) ?   $item['url'] :  $parentURL . $item['url'];
	            if (
                    $parentID + 1 == (count(self::$URL) - 1)
                    || (
                        $countSubURL === false
                        || $countSubURL >= (count(self::$URL) + ($parentID + 1))
                    )
                ) {
                    $url    =   Array();
                    for ($i = 0, $iMax = ($parentID + 2); $i < $iMax; $i++) {
                        $url[]    =  self::$URL[$i];
                    }
                    $subURL   =   Array();
                    for ($i = ($parentID + 2), $iMax = count(self::$URL); $i < $iMax; $i++) {
                        $subURL[] = self::$URL[$i];
                    }

                    $controller::setPageURL(implode('/', $url));
                    $controller::setSubURL($subURL);
                    return $item;
                }
                return self::searchPage(++$parentID, $item['url']);
            }
        }
        return self::$pageError;
    }



}