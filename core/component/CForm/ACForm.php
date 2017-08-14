<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 19:11
 */

namespace core\component\CForm;
use core\core;


/**
 * Class ACForm
 *
 * @package core\component\CForm
 */
abstract class ACForm
{
	/**
	 * @var array настройки
	 */
	protected static $config    =   Array();
	/**
	 * @var string режим работы
	 */
	protected static $mode    =   '';
	/**
	 * @var array подуровни
	 */
	protected static $subURL = Array();
	/**
	 * @var int количество подуровней
	 */
	protected static $countSubURL = 0;

	/**
	 * отдает шаблон
	 * @param string $template шаблон
	 * @param string $dir
	 *
	 * @return string шаблон
	 */
	protected static function getTemplate(string $template, string $dir = __DIR__): string
	{
		$dir    =   strtr($dir, Array(
			'\\' =>  '/'
		));
		$dr    =   strtr(core::getDR(), Array(
			'\\' =>  '/'
		));
		return '/' . str_replace($dr,'', $dir) . '/' . $template;
	}

    /**
     * переадресация
     * @param string $url URL
     * @param boolean $isExternal внешний адресс
     */
    protected static function redirect($url, $isExternal = false)
    {
        if ($isExternal === false && isset($_SERVER['HTTP_HOST'])) {
            $protocol = 'http';
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
            }
            $url        =   $protocol . '://' .$_SERVER['HTTP_HOST'] . $url;
        }
        header("Location: {$url}");
        exit;
    }
}