<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:47
 */

namespace core\component\CForm;
use core\core;
/**
 * Class AViewer
 *
 * @package core\component\CForm
 */
abstract class AViewer extends ACForm
{
	/**
	 * @var array ответ
	 */
	protected $answer = Array();
	/**
	 * @var array настроки просмотрщика
	 */
	protected  $viewerConfig      =   Array();

	/**
	 * Устанавливает ответ
	 * @param array $answer ответ
	 */
	public function setAnswer(array $answer = Array())
	{
		$this->answer =   $answer;
	}

	/**
	 * Отдает ответ
	 * @return array ответ
	 */
	public function getAnswer()
	{
		return $this->answer;
	}

	/**
	 * Устанавливает настроки просмотрщика
	 * @param array $viewerConfig настроки просмотрщика
	 */
	public function setConfig(array $viewerConfig = Array())
	{
		$this->viewerConfig =   $viewerConfig;
	}


	/**
	 * Умная сортировка
	 * @param array $v1 элемент массива
	 * @param array $v2 элемент массива
	 *
	 * @return int порядок
	 */
	protected function schemaSort($v1, $v2): int
	{
		if (!isset($v1[self::$mode]['order'])) {
			$v1[self::$mode]['order'] = 0;
		}
		if (!isset($v2[self::$mode]['order'])) {
			$v2[self::$mode]['order'] = 0;
		}
		if ($v1[self::$mode]['order'] === $v2[self::$mode]['order']) {
			return 0;
		}
		return ($v1[self::$mode]['order'] < $v2[self::$mode]['order'])? -1: 1;
	}

	/**
	 * Отдает подключает js и css шаблон просмотрщика
	 * @return string шаблон просмотрщика
	 */
	protected function getViewerTemplate()
	{
		$js         =   isset($this->viewerConfig['js'])        ?   $this->viewerConfig['js']   :   Array();
		$css        =   isset($this->viewerConfig['css'])       ?   $this->viewerConfig['css']   :   Array();
		if (is_array($js) && !empty($js)) {
			foreach ($js as $script) {
				if (!isset($script['isTopPosition'])) {
					$script['isTopPosition'] = false;

				}
				if (!isset($script['isUnique'])) {
					$script['isUnique'] = true;

				}
				self::$config['controller']::setJs($script['file'], $script['isTopPosition'], $script['isUnique']);
			}
		}
		if (is_array($css) && !empty($css)) {
			foreach ($css as $script) {
				if (!isset($script['isTopPosition'])) {
					$script['isTopPosition'] = true;

				}
				if (!isset($script['isUnique'])) {
					$script['isUnique'] = true;

				}
				self::$config['controller']::setCss($script['file'], $script['isTopPosition'], $script['isUnique']);
			}
		}
		if(count($this->answer) === 0) {
			$template = $this->viewerConfig['templateNoData'];
		} else {
			$template = $this->viewerConfig['template'];
		}
		return self::checkTemplate($template);
	}

	/**
	 * Проверяет шаблон
	 *
	 * @param string $template шаблоно
	 *
	 * @return string
	 */
	private static function checkTemplate($template)
	{
		if (empty($template)) {
			die('Нет шаблонов форм');
		}
		if (!file_exists($template)) {
			if (file_exists(core::getDR(true) . $template)) {
					return core::getDR() . $template;
			} elseif (file_exists(core::getDR(true) . $template . '.tpl')) {
					return core::getDR() . $template . '.tpl';
				} elseif (file_exists(self::getTemplate($template, __DIR__))) {
					return  self::getTemplate($template, __DIR__);
				} elseif (file_exists(self::getTemplate($template . '.tpl', __DIR__))) {
					return self::getTemplate($template . '.tpl', __DIR__);
				} elseif (file_exists(self::$config['controller']::getTemplate($template))) {
					return self::$config['controller']::getTemplate($template);
				} elseif (file_exists(self::$config['controller']::getTemplate($template . '.tpl', __DIR__))) {
					return self::$config['controller']::getTemplate($template . '.tpl');
				} elseif (file_exists(core::getDR(true) . self::getTemplate($template, __DIR__))) {
					return core::getDR(true) . self::getTemplate($template, __DIR__);
				} elseif (file_exists(core::getDR(true) . self::getTemplate($template . '.tpl', __DIR__))) {
					return  core::getDR(true) . self::getTemplate($template . '.tpl', __DIR__);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($template))) {
					return core::getDR(true) . self::$config['controller']::getTemplate($template);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($template . '.tpl', __DIR__))) {
					return core::getDR(true) . self::$config['controller']::getTemplate($template . '.tpl');
				} else {
					die('Не верный путь к шаблону формы' . $template);
				}
		}
	}
}