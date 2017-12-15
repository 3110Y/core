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
	protected $js = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );

	/**
	 * @var array
	 */
    protected $css = Array(
        'top'  =>  Array(),
        'bottom'  =>  Array(),
    );

    /**
     * @var
     */
    protected $dir;


	/**
	 * @param object $controller
	 */
	public function setJS($controller)
	{
	    foreach ($this->js['top'] as $js) {
            $controller::setJS(self::getTemplate($js, $this->dir), true);
        }
        foreach ($this->js['bottom'] as $js) {
            $controller::setJS(self::getTemplate($js, $this->dir), false);
        }
	}

	/**
	 * @param object $controller
	 */
	public function setCss($controller)
	{
        foreach ($this->css['top'] as $css) {
            $controller::setCss(self::getTemplate($css, $this->dir), true);
        }
        foreach ($this->css['bottom'] as $css) {
            $controller::setCss(self::getTemplate($css, $this->dir), false);
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
    public function returnInit($data = Array())
    {
        return simpleView\component::replace(self::getTemplate('template/init.tpl', $this->dir), $data);
    }
}