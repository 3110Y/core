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
	 *                              <li>mode: listing edit data listingData editData</li>
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
	 *                         <ul>
	 *                              <li>listing: string</li>
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

		if (self::$config['mode'] === 'listing' || self::$config['mode'] === 'listingData') {
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
		} elseif (self::$config['mode'] === 'edit' || self::$config['mode'] === 'editData') {
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
			$data = Array();
			foreach ($this->field as $field) {
				$data[$field] = false;
			}

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
					$data[$field]    =   $fieldComponent->preDell();
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
			$url    =   isset($_GET['back'])    ?   base64_decode($_GET['back'])    :   self::$config['url'];
			self::redirect($url);
		} elseif (self::$config['mode'] === 'save') {


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
            self::redirect(self::$config['url'] . '/edit/' . self::$config['id']);
        } else {
		    die('Режим не определен');
        }

        $this->answer['URL'] = self::$config['url'];
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
                    self::setJs($script['file'], $script['isTopPosition'], $script['isUnique']);
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
                    self::setCss($script['file'], $script['isTopPosition'], $script['isUnique']);
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
                    self::setJs($script['file'], $script['isTopPosition'], $script['isUnique']);
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
                    self::setCss($script['file'], $script['isTopPosition'], $script['isUnique']);
                }
            }
            $this->answer   =   simpleView\component::replace($template, $this->answer);
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
					)
					|| (
						$json
						&& (
							self::$config['sub'][count(self::$config['sub']) - 2] === 'editData'
							||  self::$config['sub'][count(self::$config['sub']) - 2] === 'listingData'
							||  self::$config['sub'][count(self::$config['sub']) - 2] === 'data'
						)
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
                    )
                    || (
                        $json
                        && (
                            self::$config['sub'][0] === 'editData'
                            ||  self::$config['sub'][0] === 'listingData'
                            ||  self::$config['sub'][0] === 'data'
                        )
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
			case 'listingData':
			case 'listing':
				if (!isset(self::$config['page'])) {
					if (count(self::$config['sub']) >= 2) {
						self::$config['page'] =   (int)end(self::$config['sub']);
					} else {
						self::$config['page'] = 1;
					}
				}
                $paginatorKey   =   'paginator' . self::$config['url'] . self::$config['mode'];
                if (isset($_GET['onPage'])) {
                    self::$config['onPage'] =  (int)$_GET['onPage'];
                    setcookie($paginatorKey, self::$config['onPage'], time() + 2592000, '/');
                } elseif (isset($_COOKIE[$paginatorKey])) {
                    self::$config['onPage']  =   $_COOKIE[$paginatorKey];
                } elseif (!isset(self::$config['onPage'])) {
                    self::$config['onPage'] = 10;
                }
				break;
			case 'editData':
			case 'dell':
			case 'edit':
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

		if (!isset(self::$config['paginator'])) {
			self::$config['paginator']  =   Array(10,15,25,30,50,75,100);
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
		if ((
		    self::$config['mode'] === 'edit'
		    || self::$config['mode'] === 'dell'
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
		if (self::$config['mode'] === 'listing' || self::$config['mode'] === 'listingData') {
			$order = '';
			if (isset($_GET['order'])) {
				$order  =   $_GET['order'];
			}
			$limit = Array(
				((self::$config['onPage'] * self::$config['page']) - self::$config['onPage']),
				self::$config['onPage']
			);
			$this->answer['ROW_ALL']    = $db->selectCount(self::$config['table'], $this->field, $where, $order);
			$this->answer['ROW_FORM']   = ((self::$config['onPage'] * self::$config['page']) - self::$config['onPage'])+1;
			$this->answer['ROW_TO']     = $this->answer['ROW_FORM']+ self::$config['onPage']-1;
			if ($this->answer['ROW_TO'] > $this->answer['ROW_ALL']) {
				$this->answer['ROW_TO'] =  $this->answer['ROW_ALL'];
			}
			$this->answer['PAGINATOR'] = $this->getPaginator();
			$this->answer['ON_PAGE'] =  self::$config['onPage'];
			foreach (self::$config['paginator'] as $page) {
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
	private function getPaginator() :array
	{
		if(self::$config['parent'] !== false) {
			$url = self::$config['url'] . '/' . self::$config['mode'] . '/';
		} else {
			$url = self::$config['url'] . '/' . self::$config['parent'] . self::$config['mode'] . '/';
		}
		$paginator  =   Array();
		$totalPages =   ceil ($this->answer['ROW_ALL'] / self::$config['onPage']);
		if ($totalPages === 1) {
			$paginator[] = Array(
				'HREF'  =>  $url . 1,
				'TEXT'  =>  'Вся информация размещена на одной странице',
				'CLASS' =>  'uk-active'
			);
		} elseif ($totalPages <= 6) {
			if (self::$config['page'] != '1') {
				$paginator[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1; $i <= $totalPages; $i++) {
				$paginator[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			if (self::$config['page'] != $totalPages) {
				$paginator[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}

		} elseif (self::$config['page']  <= 4) {
			if (self::$config['page'] != '1') {
				$paginator[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1, $iMax = 4; $i < $iMax; $i++) {
				$paginator[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			$paginator[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . $totalPages,
				'TEXT'  =>  $totalPages,
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] + 1),
				'TEXT'  =>  '<span uk-pagination-next></span>',
			);

		} elseif (self::$config['page'] >=  ($totalPages - 4)) {
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] - 1),
				'TEXT'  =>  '<span uk-pagination-previous></span>',
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . 1,
				'TEXT'  =>  1,
			);
			$paginator[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			for ($i = ($totalPages - 5), $iMax = $totalPages; $i < $iMax; $i++) {
				$paginator[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			if (self::$config['page'] != $totalPages) {
				$paginator[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . (self::$config['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}
		} else {
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] - 1),
				'TEXT'  =>  '<span uk-pagination-previous></span>',
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . 1,
				'TEXT'  =>  1,
			);
			$paginator[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			for ($i = (self::$config['page'] - 2), $iMax = (self::$config['page'] + 2); $i < $iMax; $i++) {
				$paginator[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == self::$config['page'])   ?   'uk-active' :   ''
				);
			}
			$paginator[] = Array(
				'CLASS' =>  'uk-disabled',
				'HREF'  =>  '#',
				'TEXT'  =>  '...',
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . $totalPages,
				'TEXT'  =>  $totalPages,
			);
			$paginator[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . (self::$config['page'] + 1),
				'TEXT'  =>  '<span uk-pagination-next></span>',
			);
		}
		return $paginator;
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
	 * @return array массив для ответа
	 */
    public  function getIncomingArray(): array
    {
	    $this->incomingArray[$this->incomingKey] = $this->answer;
	    return $this->incomingArray;
	}


}