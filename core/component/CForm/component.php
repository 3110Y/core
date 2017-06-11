<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 27.4.2017
 * Time: 18:55
 */

namespace core\component\CForm;

use \core\component\{
	templateEngine\engine\simpleView as simpleView
};
use core\core;


/**
* Class component
 *
 * @package core\component\CForm
 */
class component extends ACForm
{
	/**
	 * @var array массив для ответа
	 */
	private $incomingArray;
	/**
	 * @var string ключ массива для ответа
	 */
	private $incomingKey;
	/**
	 * @var array ответ
	 */
	private $answer         =   Array();
	/**
	 * @var string просмотрщик
	 */
	private  $viewer        =   '';
	/**
	 * @var array настроки просмотрщика
	 */
	private  $viewerConfig  =   Array();

	/**
	 * Устанавливает массив для ответа и его ключ
	 * component constructor.
	 * @param array $incomingArray массив для ответа
	 * @param string $incomingKey ключ массива для ответа
	 */
	public function __construct(array $incomingArray, string $incomingKey = '')
	{
		$this->incomingArray    =   $incomingArray;
		$this->incomingKey      =   $incomingKey;
	}

	/**
	 * Устанавливает настройки
	 *
	 * @param array $config настройки
	 *
	 */
	public function setConfig(array $config = Array())
	{
		self::$config           =   $config;
		if (!isset(self::$config['controller'])) {
			die('Не указан контроллер');
		}
		if (!isset(self::$config['db'])) {
			die('Нет подключения к БД');
		}
		if (!isset(self::$config['table'])) {
			die('Не указана таблица');
		}
		if (!isset(self::$config['defaultMode'])) {
			die('Не указан режим просмотрщика по умолчанию');
		}
		if (!isset(self::$config['viewer'])) {
			die('Не указаны просмотрщики');
		}
		self::$subURL           =   self::$config['controller']::getSubURL();
		self::$countSubURL      =   count(self::$subURL);

		if (self::$countSubURL >= 2 && isset(self::$subURL[self::$countSubURL - 2], self::$config['viewer'][self::$subURL[0]])) {
			self::$mode   =   self::$subURL[self::$countSubURL - 2];
		} elseif (self::$countSubURL === 1 && isset(self::$config['viewer'][self::$subURL[0]])) {
			self::$mode   =   self::$subURL[0];
		} elseif (isset(self::$config['viewer'][self::$config['defaultMode']])) {
			self::$mode   =   self::$config['defaultMode'];
		}
		if (self::$mode === '') {
			die('Неверный режим просмотрщика');
		}
		$this->viewer       = self::$config['viewer'][self::$mode]['viewer'];
		$this->viewerConfig =  self::$config['viewer'][self::$mode];
	}

	/**
	 *  Запуск
	 */
	public function run()
	{
		if (isset(self::$config['caption'])) {
			$this->answer['CAPTION_CLASS']  =   '';
			$this->answer['CAPTION']        =   self::$config['caption'];
		} else {
			$this->answer['CAPTION_CLASS']  =   'is-hidden ';
		}
		$this->answer['URL'] = self::$subURL;
		$viewer =   $this->viewer;
		$viewer = '\core\component\CForm\viewer\\' . $viewer . '\component';
		/** @var \core\component\CForm\viewer\listing\component $viewerComponent */
		$viewerComponent  =   new $viewer();
		$viewerComponent->setConfig($this->viewerConfig);
		$viewerComponent->setAnswer($this->answer);
		$viewerComponent->init();
		$viewerComponent->run();
		$this->answer   =   $viewerComponent->getAnswer();
	}

	/**
	 * Возвращяет массив для ответа
	 * @return mixed|bool|array массив для ответа
	 */
	public  function getIncomingArray()
	{
		/** @var \core\component\application\handler\Web\AApplication $controller */
		$controller = self::$config['controller'];
		if ($controller->isAjaxRequest()) {
			return $this->answer;
		}
		$this->incomingArray[$this->incomingKey] = $this->answer;
		return $this->incomingArray;
	}
}
