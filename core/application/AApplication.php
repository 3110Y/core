<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.5.2017
 * Time: 18:13
 */

namespace core\application;


/**
 * Class AApplication
 * @package core\application
 */
abstract class AApplication
{
    /**
     * @var array  приложение
     */
    protected static $application = Array();
    /**
     * @var array структура контента
     */
    protected static $content = Array();
	/**
	 * @var bool AJAX запрос
	 */
    protected static $isAjaxRequest = false;
    /**
     * @var array URL
     */
    protected static $URL = Array();
    /**
     * @var array структура приложения
     */
    protected static $structure = array();
    /**
     * @var array текущая страница
     */
    protected static $page = Array();
    /**
     * @var array страница для ошибок
     */
    protected static $pageError = Array();


    /**
     * отдает шаблон из темы
     * @param string $template шаблон
     * @return string шаблон
     */
    public static function getTemplate(string $template): string
    {
        $path       =   self::$application['path'];
        $theme      =   self::$application['theme'];
        return "/application/{$path}/theme/{$theme}/{$template}";
    }



    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected static function redirect($url, $isExternal = false)
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol   =   isset($_SERVER['HTTP_X_FORWARDED_PROTO'])   ?   $_SERVER['HTTP_X_FORWARDED_PROTO']  :   'http';
            $url        =   $protocol . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }



	/**
	 * Проверяет запрос на аяксовость
	 * @return bool
	 */
    public static function isAjaxRequest(): bool
    {
    	return self::$isAjaxRequest;
    }

	/**
	 * Отдает настройки текущей страницы
	 *
	 * @return array текущая страница
	 */
	public static function getPage()
	{
		return self::$page;
	}

	/**
	 * Отдает настройки страница для страницы
	 *
	 * @return array страница для ошибок
	 */
	public static function getPageError()
	{
		return self::$pageError;
	}

	/**
	 * Отдает настройки приложения
	 *
	 * @return array приложение
	 */
	public static function getApplication()
	{
		return self::$application;
	}

	/**
	 * Отдает структуру приложения
	 *
	 * @return array структура приложения
	 */
	public static function getStructure()
	{
		return self::$structure;
	}

	/**
	 * Отдает структуру контента
	 * @return array структура контента
	 */
	public static function getContent()
	{
		return self::$content;
	}
}