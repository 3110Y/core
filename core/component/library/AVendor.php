<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.11.2017
 * Time: 21:22
 */

namespace core\component\library;


use \core\component\{
        resources\resources,
        simpleView\simpleView,
        dir\dir
    };

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


	public function setJS()
	{
	    foreach ($this->js['top'] as $js) {
            resources::setJS(self::getTemplate($js, $this->dir), true);
        }
        foreach ($this->js['bottom'] as $js) {
            resources::setJS(self::getTemplate($js, $this->dir), false);
        }
	}

	public function setCss()
	{
        foreach ($this->css['top'] as $css) {
            resources::setCss(self::getTemplate($css, $this->dir), true);
        }
        foreach ($this->css['bottom'] as $css) {
            resources::setCss(self::getTemplate($css, $this->dir), false);
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
        $dr    =   strtr(dir::getDR(), Array(
            '\\' =>  '/'
        ));
        return '/' . str_replace($dr,'', $dir) . '/' . $template;
    }

    /**
     * @param array $data
     * @param string $name
     * @return string
     */
    public function returnInit($data = Array(), $name = 'init.tpl')
    {
        return simpleView::replace(self::getTemplate('template/' . $name, $this->dir), $data);
    }
}