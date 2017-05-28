<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 13:37
 */

namespace core\component\CForm;


/**
 * Class AField
 *
 * @package core\component\CForm
 */
abstract class AField extends AComponent
{

	/**
	 * @var string значение поля
	 */
	protected $fieldValue       =   '';
    /**
     * @var array поля для запроса
     */
    private $field  =   Array();

	/**
	 * Устанавливает  значение поля
	 * @param string $fieldValue значение поля
	 */
	public function setFieldValue(string $fieldValue)
	{
		$this->fieldValue = $fieldValue;
	}

	/**
	 * Устанавливает Заголовок
	 * @param string $fieldCaption схема поля
	 */
	public function setFieldCaption($fieldCaption)
	{
		$this->answer['CAPTION'] = $fieldCaption;
	}

    /**
     * Задает поля для запроса
     * @param array $field поля для запроса
     */
	public function setField(array $field = Array())
    {
        $this->field = $field;
    }

    /**
     * Отдает поля для запроса
     * @return array поля для запроса
     */
    public function getField(): array
    {
        return $this->field;
    }


}