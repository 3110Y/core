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
	private $incomingArray      =   Array();
	/**
	 * @var string ключ массива для ответа
	 */
	private $incomingKey        =   '';
	/**
	 * @var array ответ
	 */
	private $answer = Array();
	/**
	 * @var string просмотрщик
	 */
	private  $viewer            =   '';
	/**
	 * @var array настроки просмотрщика
	 */
	private  $viewerConfig      =   Array();

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
		} elseif (self::$countSubURL == 1 && isset(self::$config['viewer'][self::$subURL[0]])) {
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





/**
 * Class component
 *
 * @package core\component\CForm
 */
class component2 extends ACForm
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
	 * @var array ответ
	 */
	private $answer = Array();


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
	}

	/**
	 * Устанавливает схему
	 * @param array $schema схема
	 */
	public function setSchema(array $schema = Array())
	{
		self::$schema   =   $schema;
	}

	/**
	 * @param array $templates шаблоны
	 */
	public function setTemplate(array $templates)
	{
		$this->templates    =   $templates;
		$this->checkTemplate();
	}

	/**
	 *  Запуск
	 */
	public function run()
	{
		$this->checkConfig();
		usort(self::$schema,Array($this, 'schemaSort'));
		$this->field[] =    'id';
		foreach (self::$schema as $key => $field) {
			if (
				(
					isset($field["view"])
					&& $field["view"] === false
				)
				|| (
					isset($field[self::$config['mode']]["view"])
					&& $field[self::$config['mode']]["view"] === false
				)
			) {
				unset(self::$schema[$key]);
				continue;
			}
			$this->field[] = $field['field'];
		}
		$this->data =   $this->fillData();
		if (isset(self::$config['caption'])) {
			$this->answer['CAPTION_CLASS']  =   '';
			$this->answer['CAPTION']        =   self::$config['caption'];
		} else {
			$this->answer['CAPTION_CLASS']  =   'is-hidden ';
		}

		if (self::$config['mode'] === 'listing') {
			$header                     =   Array();
			$this->answer['HEADER_ROW'] =   Array();
			$this->answer['ROWS']       =   Array();
			for ($i = 0, $iMax = count($this->data); $i < $iMax; $i++) {
				$coll   =   Array();

				if (self::$config['action_rows']) {
					/** @var \core\component\CForm\field\actionID\component $fieldComponent */
					$fieldComponent = field\actionID\component::class;
					$fieldComponent::setData($this->data[$i]);
					$fieldComponent  =   new $fieldComponent();
					$fieldComponent->init();
					if (method_exists($fieldComponent, self::$config['mode'])) {
						$mode   =   self::$config['mode'];
						$fieldComponent->$mode();
					} else {
						$fieldComponent->run();
					}
					$answer = $fieldComponent->get();
					if (!isset($answer['COMPONENT'])) {
						$answer['COMPONENT']    = '';
					}
					if (!isset($answer['CLASS'])) {
						$answer['CLASS']    = '';
					}
					if (!isset($answer['STYLE'])) {
						$answer['STYLE']    = '';
					}
					if (!isset($answer['ID'])) {
						$answer['ID']    = '';
					}
					if (!isset($answer['CAPTION'])) {
						$answer['CAPTION']    = '';
					}

					$header['action_rows'] = Array(
						'COMPONENT'     =>  $answer['CAPTION'],
						'CLASS'         =>  $answer['CLASS'],
						'STYLE'         =>  $answer['STYLE'],
						'ID'            =>  'header-' . $answer['ID']
					);
					$coll['FIELDS'][]     =   Array(
						'COMPONENT'     =>  $answer['COMPONENT'],
						'CLASS'         =>  $answer['CLASS'],
						'STYLE'         =>  $answer['STYLE'],
						'ID'            =>  $answer['ID']
					);
				}

				/** поля для листинга */
				foreach (self::$schema as $key => $field) {
					/** @var \core\component\CForm\field\input\component $fieldComponent */
					$fieldComponent = '\core\component\CForm\field\\' . $field['type'] . '\component';
					$fieldComponent::setData($this->data[$i]);
					$fieldComponent  =   new $fieldComponent();
					$fieldComponent->setComponentSchema($field);
					if (isset($this->data[$i][$field['field']])) {
						$fieldComponent->setFieldValue($this->data[$i][$field['field']]);
					}
					$fieldComponent->init();
					if (method_exists($fieldComponent, self::$config['mode'])) {
						$mode   =   self::$config['mode'];
						$fieldComponent->$mode();
					} else {
						$fieldComponent->run();
					}
					$answer = $fieldComponent->get();
					if (!isset($answer['COMPONENT'])) {
						$answer['COMPONENT']    = '';
					}
					if (!isset($answer['CLASS'])) {
						$answer['CLASS']    = '';
					}
					if (!isset($answer['STYLE'])) {
						$answer['STYLE']    = '';
					}
					if (!isset($answer['ID'])) {
						$answer['ID']    = '';
					}
					if (!isset($answer['CAPTION'])) {
						$answer['CAPTION']    = '';
					}
					$header[$key] = Array(
						'COMPONENT' =>  $answer['CAPTION'],
						'CLASS'     =>  $answer['CLASS'],
						'STYLE'     =>  $answer['STYLE'],
						'ID'        =>  'header-' . $answer['ID']
					);
					$coll['FIELDS'][]     =   Array(
						'COMPONENT'     =>  $answer['COMPONENT'],
						'CLASS'     =>  $answer['CLASS'],
						'STYLE'     =>  $answer['STYLE'],
						'ID'        =>  $answer['ID']
					);
					$this->data[$i]     =   $fieldComponent::getData();
				}


				$collAction   =   Array();
				$coll['CLASS_ROW']   =   '';
				if (
					self::$config['action_row']
					&& isset(self::$config['action'], self::$config['action']['row'])
					&& !empty(self::$config['action']['row'])
				) {
					foreach (self::$config['action']['row'] as $action => $value) {
						/** @var \core\component\CForm\action\dell\component $actionComponent */
						$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
						$actionComponent::setData($this->data[$i]);
						$actionComponent  =   new $actionComponent();
						$actionComponent->setComponentSchema($value);
						$actionComponent->init();
						if (method_exists($actionComponent, 'row')) {
							$actionComponent->row();
						} else {
							$actionComponent->run();
						}
						$answer = $actionComponent->get();
						if (!isset($answer['COMPONENT'])) {
							$answer['COMPONENT']    = '';
						}
						if (!isset($answer['CLASS'])) {
							$answer['CLASS']    = '';
						}
						if (!isset($answer['STYLE'])) {
							$answer['STYLE']    = '';
						}
						if (!isset($answer['ID'])) {
							$answer['ID']    = '';
						}
						$collAction[]     =   Array(
							'COMPONENT'     =>  $answer['COMPONENT'],
							'CLASS'         =>  $answer['CLASS'],
							'STYLE'         =>  $answer['STYLE'],
							'ID'            =>  $answer['ID']
						);
						$this->data[$i]     =   $actionComponent::getData();
					}
				}
				$coll['ACTION_ROW'] =   $collAction;
				if(empty($collAction)) {
					$coll['CLASS_ROW'] = 'is-hidden ';
				} else {
					$header['ROW']   = Array(
						'COMPONENT' =>  'Действия',
						'CLASS'         =>  ' min ',
						'STYLE'         =>  '',
						'ID'            =>  '',
					);
				}
				$this->answer['ROWS'][]  =  $coll;
			}
			foreach ($header as $headerColl) {
				$this->answer['HEADER_ROW'][]   =   $headerColl;
			}
			$this->answer['CLASS_ROWS'] =   '';
			$this->answer['ACTION_ROWS']   =   Array();
			if (self::$config['action_rows']) {
				foreach (self::$config['action']['rows'] as $action => $value) {
					/** @var \core\component\CForm\action\dell\component $actionComponent */
					$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
					$actionComponent::setData($this->data);
					$actionComponent  =   new $actionComponent();
					$actionComponent->setComponentSchema($value);
					$actionComponent->init();
					if (method_exists($actionComponent, 'rows')) {
						$actionComponent->rows();
					} else {
						$actionComponent->run();
					}
					$answer = $actionComponent->get();
					if (!isset($answer['COMPONENT'])) {
						$answer['COMPONENT']    = '';
					}
					if (!isset($answer['CLASS'])) {
						$answer['CLASS']    = '';
					}
					if (!isset($answer['STYLE'])) {
						$answer['STYLE']    = '';
					}
					if (!isset($answer['ID'])) {
						$answer['ID']    = '';
					}
					$this->answer['ACTION_ROWS'][]     =   Array(
						'COMPONENT'    =>  $answer['COMPONENT'],
						'CLASS'     =>  $answer['CLASS'],
						'STYLE'     =>  $answer['STYLE'],
						'ID'        =>  $answer['ID'],
					);
					$this->data     =   $actionComponent::getData();
				}
			}
			if(empty($this->answer['ACTION_ROWS'])) {
				$this->answer['CLASS_ROWS'] = 'is-hidden ';
			}
		} elseif (self::$config['mode'] === 'edit') {
			$this->answer['FIELDS']  =  Array();

			foreach (self::$schema as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent::setData($this->data);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				if (isset($this->data[$field['field']])) {
					$fieldComponent->setFieldValue($this->data[$field['field']]);
				}
				$fieldComponent->init();
				if (method_exists($fieldComponent, self::$config['mode'])) {
					$mode   =   self::$config['mode'];
					$fieldComponent->$mode();
				} else {
					$fieldComponent->run();
				}
				$answer = $fieldComponent->get();
				if (!isset($answer['COMPONENT'])) {
					$answer['COMPONENT']    = '';
				}
				if (!isset($answer['CLASS'])) {
					$answer['CLASS']    = '';
				}
				if (!isset($answer['STYLE'])) {
					$answer['STYLE']    = '';
				}
				if (!isset($answer['ID'])) {
					$answer['ID']    = '';
				}
				if (!isset($answer['CAPTION'])) {
					$answer['CAPTION']    = '';
				}
				$this->answer['FIELDS'][]     =   Array(
					'COMPONENT'     =>  $answer['COMPONENT'],
					'CLASS'     =>  $answer['CLASS'],
					'STYLE'     =>  $answer['STYLE'],
					'ID'        =>  $answer['ID']
				);
				$this->data     =   $fieldComponent::getData();
			}
			if (
				self::$config['action_item']
				&& isset(self::$config['action'], self::$config['action']['item'])
				&& !empty(self::$config['action']['item'])
			) {
				$itemAction = Array();
				foreach (self::$config['action']['item'] as $action => $value) {
					/** @var \core\component\CForm\action\dell\component $actionComponent */
					$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
					$actionComponent::setData($this->data);
					$actionComponent  =   new $actionComponent();
					$actionComponent->setComponentSchema($value);
					$actionComponent->init();
					if (method_exists($actionComponent, 'item')) {
						$actionComponent->item();
					} else {
						$actionComponent->run();
					}
					$answer = $actionComponent->get();
					if (!isset($answer['COMPONENT'])) {
						$answer['COMPONENT']    = '';
					}
					if (!isset($answer['CLASS'])) {
						$answer['CLASS']    = '';
					}
					if (!isset($answer['STYLE'])) {
						$answer['STYLE']    = '';
					}
					if (!isset($answer['ID'])) {
						$answer['ID']    = '';
					}
					$itemAction[]     =   Array(
						'COMPONENT'     =>  $answer['COMPONENT'],
						'CLASS'         =>  $answer['CLASS'],
						'STYLE'         =>  $answer['STYLE'],
						'ID'            =>  $answer['ID']
					);
					$this->data     =   $actionComponent::getData();
				}

				$this->answer['ACTION_ITEM']     =   $itemAction;

			} else {
				$this->answer['CLASS_ACTION_ITEM'] = 'is-hidden ';
			}

			if (
				self::$config['action_bottomItem']
				&& isset(self::$config['action'], self::$config['action']['bottomItem'])
				&& !empty(self::$config['action']['bottomItem'])
			) {
				$itemAction = Array();
				foreach (self::$config['action']['bottomItem'] as $action => $value) {
					/** @var \core\component\CForm\action\dell\component $actionComponent */
					$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
					$actionComponent::setData($this->data);
					$actionComponent  =   new $actionComponent();
					$actionComponent->setComponentSchema($value);
					$actionComponent->init();
					if (method_exists($actionComponent, 'bottomItem')) {
						$actionComponent->bottomItem();
					} else {
						$actionComponent->run();
					}
					$answer = $actionComponent->get();
					if (!isset($answer['COMPONENT'])) {
						$answer['COMPONENT']    = '';
					}
					if (!isset($answer['CLASS'])) {
						$answer['CLASS']    = '';
					}
					if (!isset($answer['STYLE'])) {
						$answer['STYLE']    = '';
					}
					if (!isset($answer['ID'])) {
						$answer['ID']    = '';
					}
					$itemAction[]     =   Array(
						'COMPONENT'     =>  $answer['COMPONENT'],
						'CLASS'         =>  $answer['CLASS'],
						'STYLE'         =>  $answer['STYLE'],
						'ID'            =>  $answer['ID']
					);
					$this->data     =   $actionComponent::getData();
				}

				$this->answer['ACTION_BOTTOM_ITEM']     =   $itemAction;

			} else {
				$this->answer['CLASS_ACTION_BOTTOM_ITEM'] = 'is-hidden ';
			}

		} elseif (self::$config['mode'] === 'dell') {

			if (is_array(self::$config['id'])) {
				/** @var \core\component\database\driver\PDO\component $db */
				$db     =   self::$config['db'];
				foreach ($this->data as $id => $data) {
					/** поля для пре удаления */
					foreach (self::$schema as $key => $field) {
						/** @var \core\component\CForm\field\input\component $fieldComponent */
						$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
						$fieldComponent::setData($this->data);
						$fieldComponent  =   new $fieldComponent();
						$fieldComponent->setComponentSchema($field);
						if (isset($this->data[$field['field']])) {
							$fieldComponent->setFieldValue($data[$field['field']]);
						}
						$fieldComponent->setField($this->field);
						$fieldComponent->init();
						if (method_exists($fieldComponent, 'preDell')) {
							$fieldComponent->preDell();
						}
						$this->field    =   $fieldComponent->getField();
					}
					$where = Array(
						'id'    =>  $data['id']
					);
					$db->dell(self::$config['table'], $where);
					/** поля для пост удаления */
					foreach (self::$schema as $key => $field) {
						/** @var \core\component\CForm\field\input\component $fieldComponent */
						$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
						$fieldComponent  =   new $fieldComponent();
						$fieldComponent->setComponentSchema($field);
						$fieldComponent->init();
						if (method_exists($fieldComponent, 'postDell')) {
							$fieldComponent->postDell();
						}
					}
				}
			} elseif(is_array($this->data)) {
				/** поля для пре удаления */
				foreach (self::$schema as $key => $field) {
					/** @var \core\component\CForm\field\input\component $fieldComponent */
					$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
					$fieldComponent::setData($this->data);
					$fieldComponent  =   new $fieldComponent();
					$fieldComponent->setComponentSchema($field);
					if (isset($this->data[$field['field']])) {
						$fieldComponent->setFieldValue($this->data[$field['field']]);
					}
					$fieldComponent->setField($this->field);
					$fieldComponent->init();
					if (method_exists($fieldComponent, 'preDell')) {
						$fieldComponent->preDell();
					}
					$this->field    =   $fieldComponent->getField();
				}
				$where = Array(
					'id'    =>  self::$config['id']
				);
				/** @var \core\component\database\driver\PDO\component $db */
				$db     =   self::$config['db'];
				$db->dell(self::$config['table'], $where);

				/** поля для пост удаления */
				foreach (self::$schema as $key => $field) {
					/** @var \core\component\CForm\field\input\component $fieldComponent */
					$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
					$fieldComponent  =   new $fieldComponent();
					$fieldComponent->setComponentSchema($field);
					$fieldComponent->init();
					if (method_exists($fieldComponent, 'postDell')) {
						$fieldComponent->postDell();
					}
				}
			}
			if (isset($_GET['back'])) {
				$url = base64_decode($_GET['back']);
			} else {
				$url = self::$config['controller']::getPageURL();
			}
			self::redirect($url);
		} elseif (self::$config['mode'] === 'save') {
			$error  = Array(
				'danger'    => Array(),
				'warning'  => Array()
			);
			$value   = Array();
			/** поля для пре обновления */
			foreach (self::$schema as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent::setData($_POST);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				if (isset($_POST[$field['field']])) {
					$fieldComponent->setFieldValue($_POST[$field['field']]);
				}
				$fieldComponent->setField($this->field);
				$fieldComponent->init();
				$answer = Array();
				if (method_exists($fieldComponent, 'preUpdate')) {
					$answer =    $fieldComponent->preUpdate();
				}
				if (isset($answer['value'])) {
					$value[$field['field']] = $answer['value'];
				} else {
					$value[$field['field']] = $_POST[$field['field']];
				}
				if (isset($answer['error'])) {
					$error['danger'][] = $answer['error'];
				}
			}
			if (!empty($error['danger'])) {
				$this->answer   =   $error;
				return;
			}
			$where = Array(
				'id'    =>  self::$config['id']
			);
			/** @var \core\component\database\driver\PDO\component $db */
			$db     =   self::$config['db'];
			$db->update(self::$config['table'], $value, $where);
			self::$config['id'] =   $db->getLastID();
			$this->data         =   $db->selectRow(self::$config['table'], $this->field, $where);

			/** поля для пост обновления */
			foreach (self::$schema as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent::setData($this->data);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				if (isset($this->data[$field['field']])) {
					$fieldComponent->setFieldValue($this->data[$field['field']]);
				}
				$fieldComponent->setField($this->field);
				$fieldComponent->init();
				if (method_exists($fieldComponent, 'postUpdate')) {
					$err = $fieldComponent->postUpdate();
					if ($err !== '') {
						$error['warning'][] = $err;
					}
				}
			}
			if (!empty($error['danger']) || !empty($error['warning'])) {
				$this->answer   =   $error;
			} else {
				$this->answer   =   true;
			}
			return;
		} elseif (self::$config['mode'] === 'add') {
			$data = Array();
			foreach ($this->field as $field) {
				$data[$field] = false;
			}
			/** поля для пре сохранения */
			foreach (self::$schema as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				$fieldComponent->setField($this->field);
				$fieldComponent->init();
				if (method_exists($fieldComponent, 'preInsert')) {
					$data[$field]    =   $fieldComponent->preInsert();
				}
				$this->field    =   $fieldComponent->getField();
			}

			$value = Array();
			foreach ($data as $key => $value) {
				if ($value !== false) {
					$value[$key] = $key;
				}
			}
			$value['status'] = 3;
			/** @var \core\component\database\driver\PDO\component $db */
			$db     =   self::$config['db'];
			$db->inset(self::$config['table'], $value);
			self::$config['id'] =   $db->getLastID();
			$this->data         =   $db->selectRow(self::$config['table'],
				$this->field,
				Array(
					'id' => self::$config['id']
				));

			/** поля для пост сохранения */
			foreach (self::$schema as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent  = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent::setData($this->data);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				if (isset($this->data[$field['field']])) {
					$fieldComponent->setFieldValue($this->data[$field['field']]);
				}
				$fieldComponent->init();
				if (method_exists($fieldComponent, 'postInsert')) {
					$fieldComponent->postInsert();
				}
				$this->data     =   $fieldComponent::getData();
			}
			self::redirect(self::$config['controller']::getPageURL() . '/edit/' . self::$config['id']);
		} else {
			die('Режим не определен');
		}

		$this->answer['URL'] = self::$config['controller']::getPageURL();
		if (self::$config['mode'] === 'listing') {
			if(count($this->answer) === 0) {
				if (is_string($this->templates['listingNo'])) {
					$this->templates['listingNo'] = Array(
						'template'  =>  $this->templates['listingNo'],
						'js'        =>  Array(),
						'css'       =>  Array(),
					);
				}
				$template   =   $this->templates['listingNo']['template'];
				$js         =   $this->templates['listingNo']['js'];
				$css        =   $this->templates['listingNo']['css'];
			} else {
				if (is_string($this->templates['listing'])) {
					$this->templates['listing'] = Array(
						'template'  =>  $this->templates['listing'],
						'js'        =>  Array(),
						'css'       =>  Array(),
					);
				}
				$template   =   $this->templates['listing']['template'];
				$js         =   $this->templates['listing']['js'];
				$css        =   $this->templates['listing']['css'];
			}
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

			$this->answer   =   simpleView\component::replace($template, $this->answer);
		} elseif (self::$config['mode'] === 'edit') {
			if (is_string($this->templates['form'])) {
				$this->templates['form'] = Array(
					'template'  =>  $this->templates['form'],
					'js'        =>  Array(),
					'css'       =>  Array(),
				);
			}
			$template   =   $this->templates['form']['template'];
			$js         =   $this->templates['form']['js'];
			$css        =   $this->templates['form']['css'];
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
			$this->answer['ID'] = self::$config['id'];
			$this->answer   =   simpleView\component::replace($template, $this->answer);
		}
	}

	/**
	 * Проверяет конфиг шаблонов
	 */
	private function checkTemplate()
	{
		if (empty($this->templates)) {
			die('Нет шаблонов форм');
		}
		foreach ($this->templates as $mode => $config) {
			if (!file_exists($config['template'])) {
				if (file_exists(core::getDR(true) . $config['template'])) {
					$this->templates[$mode]['template'] = core::getDR() . $config['template'];
				} elseif (file_exists(core::getDR(true) . $config['template'] . '.tpl')) {
					$this->templates[$mode]['template'] = core::getDR() . $config['template'] . '.tpl';
				} elseif (file_exists(self::getTemplate($config['template'], __DIR__))) {
					$this->templates[$mode]['template'] = self::getTemplate($config['template'], __DIR__);
				} elseif (file_exists(self::getTemplate($config['template'] . '.tpl', __DIR__))) {
					$this->templates[$mode]['template'] = self::getTemplate($config['template'] . '.tpl', __DIR__);
				} elseif (file_exists(self::$config['controller']::getTemplate($config['template']))) {
					$this->templates[$mode]['template'] = self::$config['controller']::getTemplate($config['template']);
				} elseif (file_exists(self::$config['controller']::getTemplate($config['template'] . '.tpl', __DIR__))) {
					$this->templates[$mode]['template'] = self::$config['controller']::getTemplate($config['template'] . '.tpl');
				} elseif (file_exists(core::getDR(true) . self::getTemplate($config['template'], __DIR__))) {
					$this->templates[$mode]['template'] = core::getDR(true) . self::getTemplate($config['template'], __DIR__);
				} elseif (file_exists(core::getDR(true) . self::getTemplate($config['template'] . '.tpl', __DIR__))) {
					$this->templates[$mode]['template'] = core::getDR(true) . self::getTemplate($config['template'] . '.tpl', __DIR__);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($config['template']))) {
					$this->templates[$mode]['template'] = core::getDR(true) . self::$config['controller']::getTemplate($config['template']);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($config['template'] . '.tpl', __DIR__))) {
					$this->templates[$mode]['template'] = core::getDR(true) . self::$config['controller']::getTemplate($config['template'] . '.tpl');
				} else {
					die('Не верный путь к шаблону формы' . $config['template']);
				}
			}
		}
	}

	/**
	 * Проверяет конфиг
	 */
	private function checkConfig()
	{
		if (!isset(self::$config['db'])) {
			die('Нет подключения к БД');
		}
		if (!isset(self::$config['table'])) {
			die('Не указана таблица');
		}
		if (!isset(self::$config['controller'])) {
			die('Не указан контроллер');
		}
		/** @var \core\component\application\handler\Web\AControllers $controller */
		$controller = self::$config['controller'];
		self::$config['sub']    =   $controller::getSubURL();
		$json = false;
		if (isset($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_X_REQUESTED_WITH']) &&
			$_SERVER['HTTP_REFERER'] !== '' &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			$json = true;
		}
		if (!isset(self::$config['mode'])) {
			if (
				count(self::$config['sub']) >= 2
				&& (
					isset(self::$config['sub'][count(self::$config['sub']) - 2])
					&& (
						self::$config['sub'][count(self::$config['sub']) - 2] === 'listing'
						||  self::$config['sub'][count(self::$config['sub']) - 2] === 'edit'
						||  self::$config['sub'][count(self::$config['sub']) - 2] === 'dell'
						||  self::$config['sub'][count(self::$config['sub']) - 2] === 'add'
						||  self::$config['sub'][count(self::$config['sub']) - 2] === 'save'
					)
					|| (
						$json && self::$config['sub'][count(self::$config['sub']) - 2] === 'data'
					)
				)
			) {
				self::$config['mode'] = self::$config['sub'][count(self::$config['sub']) - 2];
			} elseif (count(self::$config['sub']) === 0) {
				self::$config['sub'][0]  = 'listing';
				self::$config['mode']  = 'listing';
			} elseif (
				count(self::$config['sub']) === 1
				&& (
					isset(self::$config['sub'][0])
					&& (
						self::$config['sub'][0] === 'listing'
						||  self::$config['sub'][0] === 'edit'
						||  self::$config['sub'][0] === 'dell'
						||  self::$config['sub'][0] === 'add'
						||  self::$config['sub'][0] === 'save'
					)
					|| (
						$json
						&&  self::$config['sub'][0] === 'data'
					)
				)
			) {
				self::$config['mode'] = self::$config['sub'][0];
			}
		}
		if (!isset(self::$config['mode'])) {
			die('Неверный режим компонента');
		}
		switch (self::$config['mode']) {
			case 'listing':
				if (!isset(self::$config['page'])) {
					if (count(self::$config['sub']) >= 2) {
						self::$config['page'] =   (int)end(self::$config['sub']);
					} else {
						self::$config['page'] = 1;
					}
				}
				$paginationKey   =   'pagination' . self::$config['controller']::getPageURL() . self::$config['mode'];
				if (isset($_GET['onPage'])) {
					self::$config['onPage'] =  (int)$_GET['onPage'];
					setcookie($paginationKey, self::$config['onPage'], time() + 2592000, '/');
				} elseif (isset($_COOKIE[$paginationKey])) {
					self::$config['onPage']  =   $_COOKIE[$paginationKey];
				} elseif (!isset(self::$config['onPage'])) {
					self::$config['onPage'] = 10;
				}
				break;
			case 'dell':
				if (!isset(self::$config['id'])) {
					if (count(self::$config['sub']) >= 2) {
						self::$config['id'] =   (int)end(self::$config['sub']);
					} elseif (isset($_POST['row'])) {
						self::$config['id'] = Array();
						foreach ($_POST['row'] as $key => $value) {
							self::$config['id'][] = (int)$key;
						}
					} else {
						self::$config['id'] = 0;
					}
				}
				break;
			case 'edit':
				if (!isset(self::$config['id'])) {
					if (count(self::$config['sub']) >= 2) {
						self::$config['id'] =   (int)end(self::$config['sub']);
					} else {
						self::$config['id'] = 0;
					}
				}
				break;
			case 'save':
				if (!isset(self::$config['id'])) {
					if (count(self::$config['sub']) >= 2) {
						self::$config['id'] =   (int)end(self::$config['sub']);
					} else {
						self::$config['id'] = 0;
					}
				}
				break;
			case 'data':
				if (isset($_GET['field']) && !isset(self::$config['field'])) {
					self::$config['field'] =   htmlentities(trim(strip_tags($_GET['field'])));
				}
				break;
		}
		if (!isset(self::$config['parent'])) {
			if (count(self::$config['sub']) >= 3) {
				self::$config['parent'] =   (int)self::$config['sub'][0];
			} elseif (isset(self::$config['parent_field'])) {
				self::$config['parent']  = 0;
			} else {
				self::$config['parent'] = false;
			}
		}
		if (!isset(self::$config['action'])) {
			self::$config['action'] = Array(
				'group'   => Array(
					'add'  =>  true,
					'dell'  =>  true
				),
				'row'   =>  Array(
					'edit'      =>  true,
					'dell'      =>  true,
				),
				'item'  =>  Array(
					'back'    =>  true,
					'dell'      =>  true,
					'save'      =>  true,
				),
				'bottomItem'  =>  Array(),
			);
		}
		if (!isset(self::$config['action']['rows'])) {
			self::$config['action']['rows']   = Array(
				'add'       =>  true,
				'dell'      =>  true
			);
		}
		if (!isset(self::$config['action']['row'])) {
			self::$config['action']['row']   = Array(
				'edit'      =>  true,
				'dell'      =>  true
			);
		}
		if (!isset(self::$config['action']['item'])) {
			self::$config['action']['item']   = Array(
				'back'    =>  true,
				'dell'      =>  true,
				'save'      =>  true
			);
		}
		if (!isset(self::$config['action']['rows']['add'])) {
			self::$config['action']['rows']['add']    =  true;
		}
		if (!isset(self::$config['action']['rows']['dell'])) {
			self::$config['action']['rows']['dell']    =  true;
		}
		if (!isset(self::$config['action']['row']['edit'])) {
			self::$config['action']['row']['edit']      =  true;
		}
		if (!isset(self::$config['action']['row']['dell'])) {
			self::$config['action']['row']['dell']      =  true;
		}
		if (!isset(self::$config['action']['item']['back'])) {
			self::$config['action']['item']['back']   =  true;
		}
		if (!isset(self::$config['action']['item']['dell'])) {
			self::$config['action']['item']['dell']     =  true;
		}
		if (!isset(self::$config['action']['item']['save'])) {
			self::$config['action']['item']['save']     =  true;
		}

		if (self::checkAction('rows')) {
			self::$config['action_rows']   =   true;
		} else {
			self::$config['action_rows']   =   false;
		}
		if (self::checkAction('row')) {
			self::$config['action_row']   =   true;
		} else {
			self::$config['action_row']   =   false;
		}
		if (self::checkAction('item')) {
			self::$config['action_item']   =   true;
		} else {
			self::$config['action_item']   =   false;
		}
		if (self::checkAction('bottomItem')) {
			self::$config['action_bottomItem']   =   true;
		} else {
			self::$config['action_bottomItem']   =   false;
		}
		if (!isset(self::$config['pagination'])) {
			self::$config['pagination']  =   Array(10,15,25,30,50,75,100);
		}
	}

	/**
	 * Проверка action
	 * @param string $key ключ массив self::$config['action']
	 * @return bool
	 */
	private static function checkAction(string $key): bool
	{
		$result = false;
		if (
			isset(self::$config['action'][$key])
			&& !empty(self::$config['action'][$key])
			&& is_array(self::$config['action'][$key])
		) {
			foreach (self::$config['action'][$key] as $k => $value) {
				if ($value === true) {
					$result = true;
				} else {
					unset(self::$config['action'][$key][$k]);
				}
			}
		}
		return $result;
	}

	/**
	 * Заполняет дату
	 * @return array дата
	 */
	private function fillData()
	{
		$where  =   Array();
		if (isset(self::$config[self::$config['mode']]['where'])) {
			$where    = array_merge($where, self::$config[self::$config['mode']]['where']);
		} elseif (isset(self::$config['where'])) {
			$where    = array_merge($where, self::$config['where']);
		}
		if (
			(
				self::$config['mode'] === 'edit'
				|| self::$config['mode'] === 'save'
				|| (
					self::$config['mode'] === 'dell'
					&& is_int(self::$config['id'])
				)
			)
			&& (
				!isset(self::$config['noWhere'])
				|| self::$config['noWhere'] === false
			)

		) {
			$where    = array_merge(
				$where,
				Array(
					'id'    =>  self::$config['id'],
				)
			);
		}
		if (
			self::$config['mode'] === 'dell'
			&& is_array(self::$config['id'])
			&& (
				!isset(self::$config['noWhere'])
				|| self::$config['noWhere'] === false
			)
		) {
			$w = array();
			for ($i = 0, $iMax = count(self::$config['id']); $i < $iMax; $i++) {
				$w[] = Array(
					'f' => 'id',
					'v' => self::$config['id'][$i]
				);
				if (($iMax - 1) != $i) {
					$w[] = 'OR';
				}
			}
			$where    = array_merge(
				$where,
				$w
			);
		}
		if (
			self::$config['parent'] !== false
			&& isset(self::$config['parent'], self::$config['parent_field'])
			&& (
				!isset(self::$config['noWhere'])
				|| self::$config['noWhere'] === false
			)
		) {
			$where    = array_merge(
				$where,
				Array(
					self::$config['parent_field']    =>  self::$config['parent'],
				)
			);
		}
		/** @var \core\component\database\driver\PDO\component $db */
		$db =   self::$config['db'];
		if (self::$config['mode'] === 'dell' && is_array(self::$config['id'])) {
			return $db->selectRows(self::$config['table'], $this->field, $where);
		} elseif (self::$config['mode'] === 'listing') {
			$order = '';
			if (isset($_GET['order'])) {
				$order  =   $_GET['order'];
			}
			$this->answer['ROW_ALL']    = $db->selectCount(self::$config['table'], $this->field, $where, $order);
			if (self::$config['page'] >  ceil ($this->answer['ROW_ALL'] / self::$config['onPage'])) {
				$urlBack = self::$config['controller']::getPageURL();
				if (self::$config['mode'] == 'listing') {
					$urlBack .= '/' . self::$config['mode'] . '/' . (self::$config['page'] - 1);
				}
				self::redirect($urlBack);
			}
			$limit = Array(
				((self::$config['onPage'] * self::$config['page']) - self::$config['onPage']),
				self::$config['onPage']
			);
			$this->answer['ROW_FORM']   = ((self::$config['onPage'] * self::$config['page']) - self::$config['onPage']) + 1;
			$this->answer['ROW_TO']     = $this->answer['ROW_FORM']+ self::$config['onPage'] - 1;
			if ($this->answer['ROW_TO'] > $this->answer['ROW_ALL']) {
				$this->answer['ROW_TO'] =  $this->answer['ROW_ALL'];
			}
			$this->answer['pagination'] = $this->getPagination();
			$this->answer['ON_PAGE'] =  self::$config['onPage'];
			foreach (self::$config['pagination'] as $page) {
				$this->answer['ON_PAGE_LIST'][] =  Array(
					'CLASS' =>  ($page == self::$config['onPage'])  ?   'uk-active' :   '',
					'URL'   =>  '?onPage=' . $page,
					'TEXT'  =>  $page
				);
			}
			return $db->selectRows(self::$config['table'], $this->field, $where, $order, $limit);
		} else {
			return $db->selectRow(self::$config['table'], $this->field, $where);
		}
	}

	/**
	 * Отдает Постраничку
	 * @return array данные Постранички
	 */
	private function getPagination() :array
	{
		if(self::$config['parent'] !== false) {
			$url = self::$config['controller']::getPageURL() . '/' . self::$config['mode'] . '/';
		} else {
			$url = self::$config['controller']::getPageURL() . '/' . self::$config['parent'] . self::$config['mode'] . '/';
		}
		$pagination  =   Array();
		$totalPages =   ceil ($this->answer['ROW_ALL'] / self::$config['onPage']);
		if ($totalPages === 1) {
			$pagination[] = Array(
				'HREF'  =>  $url . 1,
				'TEXT'  =>  'Вся информация размещена на одной странице',
				'CLASS' =>  'uk-active'
			);
		} elseif ($totalPages <= 6) {
			if (self::$config['page'] != '1') {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1; $i <= $totalPages; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			if (self::$config['page'] != $totalPages) {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}

		} elseif (self::$config['page']  <= 4) {
			if (self::$config['page'] != '1') {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1, $iMax = 4; $i < $iMax; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			$pagination[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . $totalPages,
				'TEXT'  =>  $totalPages,
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] + 1),
				'TEXT'  =>  '<span uk-pagination-next></span>',
			);

		} elseif (self::$config['page'] >=  ($totalPages - 4)) {
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] - 1),
				'TEXT'  =>  '<span uk-pagination-previous></span>',
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . 1,
				'TEXT'  =>  1,
			);
			$pagination[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			for ($i = ($totalPages - 5), $iMax = $totalPages; $i < $iMax; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			if (self::$config['page'] != $totalPages) {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}
		} else {
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] - 1),
				'TEXT'  =>  '<span uk-pagination-previous></span>',
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . 1,
				'TEXT'  =>  1,
			);
			$pagination[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			for ($i = (self::$config['page'] - 2), $iMax = (self::$config['page'] + 2); $i < $iMax; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			$pagination[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . $totalPages,
				'TEXT'  =>  $totalPages,
			);
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] + 1),
				'TEXT'  =>  '<span uk-pagination-next></span>',
			);
		}
		return $pagination;
	}

	/**
	 * Умная сортировка
	 * @param array $v1 элемент массива
	 * @param array $v2 элемент массива
	 *
	 * @return int порядок
	 */
	private function schemaSort($v1, $v2): int
	{
		if (!isset($v1[self::$config['mode']]['order'])) {
			$v1[self::$config['mode']]['order'] = 0;
		}
		if (!isset($v2[self::$config['mode']]['order'])) {
			$v2[self::$config['mode']]['order'] = 0;
		}
		if ($v1[self::$config['mode']]['order'] === $v2[self::$config['mode']]['order']) {
			return 0;
		}
		return ($v1[self::$config['mode']]['order'] < $v2[self::$config['mode']]['order'])? -1: 1;
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