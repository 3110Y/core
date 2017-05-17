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
	 * @var array поля для запроса
	 */
    private $field  =   Array();
	/**
	 * @var array данные
	 */
    private $data   =   Array();

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
	 *                                  <li>table: string</li>
	 *                                  <li>subURL: array</li>
	 *                                  <li>db: object</li>
	 *                                  <li>where: mixed</li>
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
		usort($this->schema,Array($this, 'schemaSort'));
		var_dump($this->schema);

		foreach ($this->schema as $key => $field) {
			if (
				(
					isset($field["view"])
					&& $field["view"] === false
				)
				|| (
					isset($field[$this->config['mode']]["view"])
				&& $field[$this->config['mode']]["view"] === false
				)
			) {
				unset($this->schema[$key]);
				continue;
			}
			$this->field[] = $field['field'];
		}
		$this->data =   $this->fillData();

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
					$this->config['sub'][count($this->config['sub']) - 1] == 'list'
					||  $this->config['sub'][count($this->config['sub']) - 1] == 'edit'
					|| (
						$json
						&& (
							$this->config['sub'][count($this->config['sub']) - 1] == 'editData'
							||  $this->config['sub'][count($this->config['sub']) - 1] == 'listData'
							||  $this->config['sub'][count($this->config['sub']) - 1] == 'data'
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
		if (!isset($this->config['table'])) {
			die('Не указана таблица');
		}
	}


	private function fillData()
	{
		$field  =   implode(', ', $this->field);
		$table  =   $this->config['table'];
		$where  =   Array();
		if (isset($this->config[$this->config['mode']]['where'])) {
			$where    = array_merge($where, $this->config[$this->config['mode']]['where']);
		} elseif (isset($this->config['where'])) {
			$where    = array_merge($where, $this->config['where']);
		}
		if (
			(
				!isset($this->config['noWhere'])
				|| $this->config['noWhere'] === false
			)
			&& $this->config['mode'] == 'edit'
		) {
			$where    = array_merge(
				$where,
				Array(
					'id'    =>  $this->config['id'],
				)
			);
		}
		if (
			(
				!isset($this->config['noWhere'])
				|| $this->config['noWhere'] === false
			)
			&& isset($this->config['parent'])
			&& $this->config['parent'] !== false
			&& isset($this->config['parent_field'])
		) {
			$where    = array_merge(
				$where,
				Array(
					$this->config['parent_field']    =>  $this->config['parent'],
				)
			);
		}
		if ($this->config['mode'] == 'list') {
			$order = '';
			$limit = Array(
				$this->config['page'],
				$this->config['onPage']
			);
			/** @var \core\component\database\driver\PDO\component $db */
			$db =   $this->config['db'];
			return $db->selectRows($table, $field, $where, $order, $limit);
		}
	}

	/**
	 * Умная сортировка
	 * @param array $v1 элемент массива
	 * @param array $v2 элемент массива
	 *
	 * @return int порядок
	 */
	private function schemaSort($v1, $v2)
	{
		if (!isset($v1[$this->config['mode']]["order"])) {
			$v1[$this->config['mode']]["order"] = 0;
		}
		if (!isset($v2[$this->config['mode']]["order"])) {
			$v2[$this->config['mode']]["order"] = 0;
		}
		if ($v1[$this->config['mode']]["order"] == $v2[$this->config['mode']]["order"]) {
			return 0;
		}
		return ($v1[$this->config['mode']]["order"] < $v2[$this->config['mode']]["order"])? -1: 1;
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