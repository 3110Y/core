<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 13:37
 */

namespace core\component\CForm;

use core as core;

/**
 * Class AField
 *
 * @package core\component\CForm
 */
abstract class AField extends ACForm
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
	 * @var array данные
	 */
	protected static $data      =   Array();
	/**
	 * @var array схема поля
	 */
	protected $fieldSchema      =   Array();
	/**
	 * @var string значение поля
	 */
	protected $fieldValue       =   '';
	/**
	 * @var mixed|string|array ответ
	 */
	protected $answer           =   null;

	/**
	 * устанавливает ответ
	 * @param string $value значение
	 */
	public function setAnswerField(string $value = '')
	{
		$this->answer['FIELD'] = $value;
	}

	/**
	 * добавляет класс
	 * @param string $class класс
	 */
	public function addAnswerClass(string $class = '')
	{
		if (!isset($this->answer['CLASS'])) {
			$this->answer['CLASS']  =   '';
		}
		$this->answer['CLASS'] .= ' ' . $class;
	}

	/**
	 * добавляет стиль
	 * @param string $style стиль
	 * @param string $value значение
	 */
	public function addAnswerStyle(string $style = '', string $value = '')
	{
		if (!isset($this->answer['STYLE'])) {
			$this->answer['STYLE']  =   '';
		}
		$this->answer['STYLE'] .= ' ' . $style;
		if ($value != '') {
			$this->answer['STYLE'] .= ": {$value};";
		}
	}

	/**
	 * устанавливает ID
	 * @param string $ID ID
	 */
	public function addAnswerID(string $ID = '')
	{
		$this->answer['ID'] = $ID;
	}

	/**
	 * Устанавливает настройки
	 * @param array $config настройки
	 */
	public static function setConfig(array $config)
	{
		self::$config   =   $config;
	}

	/**
	 * Отдает настройки
	 * @return array
	 */
	public static function getConfig()
	{
		return self::$config;
	}

	/**
	 * Устанавливает схему
	 * @param array $schema схема
	 */
	public static function setSchema(array $schema)
	{
		self::$schema   =   $schema;
	}

	/**
	 * Отдает схему
	 * @return array
	 */
	public static function getSchema()
	{
		return self::$schema;
	}

	/**
	 * Устанавливает данные
	 * @param array $data данные
	 */
	public static function setData(array $data)
	{
		self::$data =   $data;
	}

	/**
	 * Отдает данные
	 * @return array данные
	 */
	public static function getData()
	{
		return self::$data;
	}

	/**
	 * Устанавливает схему полей
	 * @param array $fieldSchema схема поля
	 */
	public function setFieldSchema(array $fieldSchema)
	{
		$this->fieldSchema = $fieldSchema;
	}

	/**
	 * Устанавливает  значение поля
	 * @param string $fieldValue значение поля
	 */
	public function setFieldValue(string $fieldValue)
	{
		$this->fieldValue = $fieldValue;
	}

	/**
	 * Ответ
	 * @return array|mixed|string
	 */
	public function get()
	{
		return $this->answer;
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
		$dr    =   strtr(core\core::getDR(), Array(
			'\\' =>  '/'
		));
		return '/' . str_replace($dr,'', $dir) . '/' . $template;
	}
}