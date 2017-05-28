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
	 * @var array схема
	 */
	protected static $schema    =   Array();

	/**
	 * @var array js файлы
	 */
	protected static $js = Array();
	/**
	 * @var array файлы
	 */
	protected static $css = Array();


	/**
	 * Задает JS
	 * @param string $file файл
	 * @param bool $isTopPosition позиция top|bottom
	 * @param bool $isUnique уникальность
	 */
	protected static function setJs(string $file, bool $isTopPosition = false, bool $isUnique = true)
	{
		self::$js[] = Array(
			'file'          =>  $file,
			'isTopPosition' =>  $isTopPosition,
			'isUnique'      =>  $isUnique
		);
	}

	/**
	 * Задает CSS
	 * @param string $file файл
	 * @param bool $isTopPosition позиция top|bottom
	 * @param bool $isUnique уникальность
	 */
	protected static function setCss(string $file, bool $isTopPosition = true, bool $isUnique = true)
	{
		self::$css[] = Array(
			'file'          =>  $file,
			'isTopPosition' =>  $isTopPosition,
			'isUnique'      =>  $isUnique
		);
	}

	/**
	 * Отдает JS
	 *
	 * @return mixed js
	 */
	public static function getJs()
	{
		return self::$js;
	}

	/**
	 * Отдает CSS
	 *
	 * @return mixed js
	 */
	public static function getCss()
	{
		return self::$css;
	}


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
}