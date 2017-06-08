<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 19.5.2017
 * Time: 13:26
 */

namespace core\component\CForm;


/**
 * Class AComponent
 *
 * @package core\component\CForm
 */
class AComponent extends ACForm
{
	/**
	 * @var array схема поля
	 */
	protected $componentSchema      =   Array();
	/**
	 * @var array данные
	 */
	protected static $data          =   Array();
	/**
	 * @var mixed|string|array ответ
	 */
	protected $answer;

	/**
	 * устанавливает ответ
	 * @param string $value значение
	 */
	public function setComponentAnswer(string $value = '')
	{
		$this->answer['COMPONENT'] = $value;
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
	public static function getData(): array
	{
		return self::$data;
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
		if ($value !== '') {
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
	 * Устанавливает схему полей
	 * @param mixed|array|boolean $componentSchema схема поля
	 */
	public function setComponentSchema($componentSchema)
	{
		$this->componentSchema = $componentSchema;
	}

	/**
	 * Ответ
	 * @return array|mixed|string
	 */
	public function get()
	{
		return $this->answer;
	}

}