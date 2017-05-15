<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 27.4.2017
 * Time: 18:55
 */

namespace core\component\CForm;

/**
* Class component
 *
 * @package core\component\CForm
 */
class component
{
	/**
	 * @var array массив для ответа
	 */
    private $incomingArray  =   Array();
	/**
	 * @var string ключ массива для ответа
	 */
    private $incomingKey    =   '';
	/**
	 * @var array настройки
	 */
    private $config =   Array();

    private $schema =   Array();

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


    public function setConfig(array $config = Array())
    {
		$this->config   =   $config;
    }

	public function setSchema(array $schema = Array())
	{
		$this->schema   =   $schema;
	}

	public function run()
	{

	}

    /**
     * Возвращяет массив для ответа
	 * @return array массив для ответа
	 */
    public  function getIncomingArray(): array
    {
        return $this->incomingArray;
	}
}