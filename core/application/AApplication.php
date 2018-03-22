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
     * @var array структура контента
     */
    protected static $content = Array();

	/**
	 * @var mixed|bool|null AJAX запрос
	 */
    private static $isAjaxRequest;

    /**
     * @var string
     */
    protected static $theme;

    /**
     * @var string
     */
    protected static $path;

    /**
     * @var \core\router\route
     */
    protected static $applicationRoute;

    /**
     * отдает шаблон из темы
     * @param string $template шаблон
     * @return string шаблон
     */
    public static function getTemplate(string $template): string
    {
        $theme  =   self::$theme;
        $path   =   self::$path;
        $DS     =   DIRECTORY_SEPARATOR;
        return "{$DS}application{$DS}{$path}{$DS}theme{$DS}{$theme}{$DS}{$template}";
    }



    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected static function redirect($url, $isExternal = false) : void
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol   = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'http';
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
        if (self::$isAjaxRequest === null) {
            self::$isAjaxRequest = (
                isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_REFERER'] !== '' &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            );
        }
    	return self::$isAjaxRequest;
    }

}