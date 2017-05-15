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
    private $config         =   Array();
	/**
	 * @var array схема
	 */
    private $schema         =   Array();
	/**
	 * @var array шаблоны
	 */
    private $templates      =   Array();

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
	 *                      <ul>
	 *                        <li>
	 *                            need
	 *                            <ul>
	 *                                  <li>url: string</li>
	 *                                  <li>subURL: array</li>
	 *                                  <li>db: object</li>
	 *                            </ul>
	 *                         </li>
	 *                      <li>
	 *                          posible
	 *                          <ul>
	 *                              <li>mode: list edit data listData editData</li>
	 *                              <li>id: 0 1 2 ... n</li>
	 *                              <li>page: 0 1 2 ... n</li>
	 *                              <li>parent: 0 1 2 ... n</li>
	 *                              <li>onPage: 0 1 2 ... n</li>
	 *                            </ul>
	 *                       </li>
	 *                      </ul>
	 */
    public function setConfig(array $config = Array())
    {
		$this->config           =   $config;
    }

	/**
	 * Устанавливает схему
	 * @param array $schema схема
	 */
	public function setSchema(array $schema = Array())
	{
		$this->schema   =   $schema;
	}

	/**
	 * @param array $templates шаблоны
	 *                         <ul>
	 *                              <li>list: string</li>
	 *                              <li>form: string</li>
	 *                          </ul>
	 */
	public function setTemplate(array $templates)
	{
		$this->templates    =   $templates;
	}

	/**
	 *  Запуск
	 */
	public function run()
	{
		$this->checkConfig();


	}

	/**
	 * Проверяет конфиг
	 */
	private function checkConfig()
	{
		$json = false;
		if (isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
			$_SERVER['HTTP_REFERER'] !== '' &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			$json = true;
		}
		if (!isset($this->config['mode'])) {
			if (
				count($this->config['sub']) >= 1
				&& (
					$this->config['sub'][count($this->config) - 1] == 'list'
					||  $this->config['sub'][count($this->config) - 1] == 'edit'
					|| (
						$json
						&& (
							$this->config['sub'][count($this->config) - 1] == 'editData'
							||  $this->config['sub'][count($this->config) - 1] == 'listData'
							||  $this->config['sub'][count($this->config) - 1] == 'data'
						)
					)
				)
			) {
					$this->config['mode'] = $this->config['sub'][count($this->config['sub']) - 1];
			}  elseif (count($this->config['sub']) == 0) {
				$this->config['sub'][0]  = 'list';
			} else {
				$this->config['sub'][count($this->config) - 1]  = 'list';
			}
			$this->config['mode'] = htmlentities(trim(strip_tags($this->config['sub'][count($this->config['sub']) - 1])));
		}
		switch ($this->config['mode']) {
			case 'listData':
			case 'list':
				if (!isset($this->config['page'])) {
					if (count($this->config['sub']) >= 2) {
						$this->config['page'] =   (int)end($this->config['sub']);
					} else {
						$this->config['page'] = 1;
					}
				}
				break;
			case 'editData':
			case 'edit':
				if (!isset($this->config['id'])) {
					if (count($this->config['sub']) >= 2) {
						$this->config['id'] =   (int)end($this->config['sub']);
					} else {
						$this->config['id'] = 0;
					}
				}
				break;
			case 'data':
				if (!isset($this->config['field'])) {
					if (isset($_GET['field'])) {
						$this->config['field'] =   htmlentities(trim(strip_tags($_GET['field'])));
					}
				}
				break;
		}
		if (!isset($this->config['parent'])) {
			if (count($this->config['sub']) >= 3) {
				$this->config['parent'] =   (int)$this->config['sub'][0];
			} elseif (isset($this->config['parent_field'])) {
				$this->config['parent']  = 0;
			} else {
				$this->config['parent'] = false;
			}
		}
		if (isset($_GET['onPage'])) {
			$this->config['onPage'] =  (int)$this->config['onPage'];
		} elseif (!isset($this->config['onPage'])) {
			$this->config['onPage'] = 30;
		}
		if (!isset($this->config['db'])) {
			die('Нет подключения к БД');
		}
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