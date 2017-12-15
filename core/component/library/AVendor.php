<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.11.2017
 * Time: 21:22
 */

namespace core\component\library;

use core\core,
    core\component\templateEngine\engine\simpleView as simpleView;

abstract class AVendor
{
	/**
	 * @var array
	 */
	protected static $js = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );

	/**
	 * @var array
	 */
    protected static $css = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );


	/**
	 * @param object $controller
	 */
	public static function setJS($controller)
	{
	    foreach (self::$js['top'] as $js) {
            $controller::setJS(self::getTemplate($js, __DIR__), true);
        }
        foreach (self::$js['bottom'] as $js) {
            $controller::setJS(self::getTemplate($js, __DIR__), false);
        }
	}

	/**
	 * @param object $controller
	 */
	public static function setCss($controller)
	{
        foreach (self::$css['top'] as $css) {
            $controller::setCss(self::getTemplate($css, __DIR__), true);
        }
        foreach (self::$css['bottom'] as $css) {
            $controller::setCss(self::getTemplate($css, __DIR__), false);
        }
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

    /**
     * @param array $data
     * @param string $dir
     * @return string
     */
    public static function returnInit($data = Array(), $dir = __DIR__)
    {
        return simpleView\component::replace(self::getTemplate('template/init.tpl', $dir), $data);
    }
}