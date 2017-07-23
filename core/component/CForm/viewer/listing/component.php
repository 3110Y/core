<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:38
 */

namespace core\component\CForm\viewer\listing;


use \core\component\{
	CForm,
	templateEngine\engine\simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\viewer\listing
 */
class component extends CForm\AViewer implements CForm\IViewer
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.1;



	public function init()
	{
		$config = self::$config;
		unset($config['viewer']);
		$this->viewerConfig = array_merge($this->viewerConfig, $config);
		$this->schemaField                =  $this->viewerConfig['field'];
		$this->viewerConfig['page']       =   $this->getPageNow();
		$this->viewerConfig['onPage']     =   $this->getOnPage();
		$this->viewerConfig['parent']     =   $this->getParent();
		if (!isset($this->viewerConfig['pagination'])) {
			$this->viewerConfig['pagination']  =   Array(10,15,25,30,50,75,100);
		}
		$this->data    =   $this->fillData();

	}

	public function run()
	{
		$header                     =   Array();
		$this->answer['HEADER_ROW'] =   Array();
		$this->answer['ROWS']       =   Array();
		for ($i = 0, $iMax = count($this->data); $i < $iMax; $i++) {
			$coll   =   Array();
			if (
				isset($this->viewerConfig['action'], $this->viewerConfig['action']['rows'])
				&& !empty($this->viewerConfig['action']['rows'])
			) {
				/** @var \core\component\CForm\field\actionID\component $fieldComponent */
				$fieldComponent = CForm\field\actionID\component::class;
				$fieldComponent::setData($this->data[$i]);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->init();
				$fieldComponent->edit();
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
			foreach ($this->schemaField as $key => $field) {
				/** @var \core\component\CForm\field\input\component $fieldComponent */
				$fieldComponent = '\core\component\CForm\field\\' . $field['type'] . '\component';
				$fieldComponent::setData($this->data[$i]);
				$fieldComponent::setData($this->data[$i]);
				$fieldComponent  =   new $fieldComponent();
				$fieldComponent->setComponentSchema($field);
				if (isset($field['field'], $this->data[$i][$field['field']])) {
					$fieldComponent->setFieldValue($this->data[$i][$field['field']]);
				}
				$fieldComponent->init();
				$mode   =   'run';
				if (isset($field[self::$mode]['mode']) && method_exists($fieldComponent, $field[self::$mode]['mode'])) {
					$mode   =   $field[self::$mode]['mode'];
				} elseif (isset($field['mode']) && method_exists($fieldComponent, $field['mode'])) {
					$mode   =   $field['mode'];
				}
				$fieldComponent->$mode();
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
				isset($this->viewerConfig['action'], $this->viewerConfig['action']['row'])
				&& !empty($this->viewerConfig['action']['row'])
			) {
				foreach ($this->viewerConfig['action']['row'] as $action => $value) {
					/** @var \core\component\CForm\action\dell\component $actionComponent */
					$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
					$actionComponent::setData($this->data[$i]);
					$actionComponent  =   new $actionComponent();
					$actionComponent->setComponentSchema($value);
					$actionComponent->init();
					$mode   =   'run';
					if (isset($value['method']) && method_exists($actionComponent, $value['method'])) {
						$mode   =   $value['method'];
					}
					$actionComponent->$mode();
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

		if (
			isset($this->viewerConfig['action'], $this->viewerConfig['action']['rows'])
			&& !empty($this->viewerConfig['action']['rows'])
		) {
			foreach ($this->viewerConfig['action']['rows'] as $action => $value) {
				/** @var \core\component\CForm\action\dell\component $actionComponent */
				$actionComponent = '\core\component\CForm\action\\' . $action . '\component';
				$actionComponent::setData($this->data);
				$actionComponent  =   new $actionComponent();
				$actionComponent->setComponentSchema($value);
				$actionComponent->init();
				$mode   =   'run';
				if (isset($value['method']) && method_exists($actionComponent, $value['method'])) {
					$mode   =   $value['method'];
				}
				$actionComponent->$mode();
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
		$template = $this->getViewerTemplate();
		$this->answer   =   simpleView\component::replace($template, $this->answer);
	}


	/**
	 * Заполняет дату
	 * @return array дата
	 */
	private function fillData(): array
    {
		$this->fillField();
		usort($this->schemaField, Array($this, 'callbackSchemaSort'));
		$where  =   $this->preparationWhere();

		/** @var \core\component\database\driver\PDO\component $db */
		$db =   $this->viewerConfig['db'];
		$order = '';
		if (isset($_GET['order'])) {
			$order  =   $_GET['order'];
		} elseif (isset($this->viewerConfig['order'])) {
            $order  =   $this->viewerConfig['order'];
        }
		$this->answer['ROW_ALL']    = $db->selectCount($this->viewerConfig['table'], $this->field, $where, $order);
		$total = (int)ceil ($this->answer['ROW_ALL'] / $this->viewerConfig['onPage']);
		if (0 !== $total && $this->viewerConfig['page'] >  $total) {
			$urlBack = $this->viewerConfig['controller']::getPageURL();
			if (isset($this->viewerConfig['mode'])) {
				$urlBack .= '/' . $this->viewerConfig['mode'] . '/' . ($this->viewerConfig['page'] - 1);
			} elseif (isset($this->viewerConfig['defaultMode'])) {
				$urlBack .= '/' . $this->viewerConfig['defaultMode'] . '/' . ($this->viewerConfig['page'] - 1);
			}
			self::redirect($urlBack);
		}
		$limit = Array(
			(($this->viewerConfig['onPage'] * $this->viewerConfig['page']) - $this->viewerConfig['onPage']),
			$this->viewerConfig['onPage']
		);
		$this->answer['ROW_FORM']   = (($this->viewerConfig['onPage'] * $this->viewerConfig['page']) - $this->viewerConfig['onPage']) + 1;
		$this->answer['ROW_TO']     = $this->answer['ROW_FORM'] + $this->viewerConfig['onPage'] - 1;
		if ($this->answer['ROW_TO'] > $this->answer['ROW_ALL']) {
			$this->answer['ROW_TO'] =  $this->answer['ROW_ALL'];
		}
		$this->answer['PAGINATION'] = $this->getPagination();
		$this->answer['ON_PAGE'] =  $this->viewerConfig['onPage'];
			foreach ($this->viewerConfig['pagination'] as $page) {
				$this->answer['ON_PAGE_LIST'][] =  Array(
					'CLASS' =>  ($page == $this->viewerConfig['onPage'])  ?   'uk-active' :   '',
					'URL'   =>  '?onPage=' . $page,
					'TEXT'  =>  $page
				);
			}
		return $db->selectRows(self::$config['table'], $this->field, $where, $order, $limit);
	}

	/**
	 * Отдает Постраничку
	 * @return array данные Постранички
	 */
	private function getPagination() :array
	{
		if ($this->viewerConfig['parent'] !== false) {
			$url = $this->viewerConfig['controller']::getPageURL() . '/' . $this->viewerConfig['mode'] . '/';
		} else {
			$url = $this->viewerConfig['controller']::getPageURL() . '/' . $this->viewerConfig['parent'] . self::$mode . '/';
		}
		$pagination  =   Array();
		$totalPages =   ceil ($this->answer['ROW_ALL'] / $this->viewerConfig['onPage']);
		if ($totalPages === 1) {
			$pagination[] = Array(
				'HREF'  =>  $url . 1,
				'TEXT'  =>  'Вся информация размещена на одной странице',
				'CLASS' =>  'uk-active'
			);
		} elseif ($totalPages <= 6) {
			if ($this->viewerConfig['page'] != '1') {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . ($this->viewerConfig['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1; $i <= $totalPages; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == $this->viewerConfig['page'])   ?   'uk-active' :   ''
				);
			}
			if ($this->viewerConfig['page'] != $totalPages) {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . ($this->viewerConfig['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}

		} elseif ($this->viewerConfig['page']  <= 4) {
			if ($this->viewerConfig['page'] != '1') {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . ($this->viewerConfig['page'] - 1),
					'TEXT'  =>  '<span uk-pagination-previous></span>',
				);
			}
			for ($i = 1, $iMax = 4; $i < $iMax; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == $this->viewerConfig['page'])   ?   'uk-active' :   ''
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

		} elseif ($this->viewerConfig['page'] >=  ($totalPages - 4)) {
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . ($this->viewerConfig['page'] - 1),
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
					'CLASS' =>  ($i == $this->viewerConfig['page'])   ?   'uk-active' :   ''
				);
			}
			if ($this->viewerConfig['page'] != $totalPages) {
				$pagination[] = Array(
					'CLASS' =>  '',
					'HREF'  =>  $url . ($this->viewerConfig['page'] + 1),
					'TEXT'  =>  '<span uk-pagination-next></span>',
				);
			}
		} else {
			$pagination[] = Array(
				'CLASS' =>  '',
				'HREF'  =>  $url . ($this->viewerConfig['page'] - 1),
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
			for ($i = ($this->viewerConfig['page'] - 2), $iMax = ($this->viewerConfig['page'] + 2); $i < $iMax; $i++) {
				$pagination[] = Array(
					'HREF'  =>  $url . $i,
					'TEXT'  =>  $i,
					'CLASS' =>  ($i == $this->viewerConfig['page'])   ?   'uk-active' :   ''
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
				'HREF'  =>  $url . ($this->viewerConfig['page'] + 1),
				'TEXT'  =>  '<span uk-pagination-next></span>',
			);
		}
		return $pagination;
	}
}