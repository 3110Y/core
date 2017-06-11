<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:47
 */

namespace core\component\CForm;
use core\core;
/**
 * Class AViewer
 *
 * @package core\component\CForm
 */
abstract class AViewer extends ACForm
{
	/**
	 * @var array ответ
	 */
	protected $answer = Array();
	/**
	 * @var array настроки просмотрщика
	 */
	protected  $viewerConfig      =   Array();
    /**
     * @var array схема полей
     */
    protected $schemaField   =   Array();
    /**
     * @var array  поля для запроса
     */
    protected $field   =   Array();
    /**
     * @var array данные
     */
    protected $data   =   Array();


    /**
	 * Устанавливает ответ
	 * @param array $answer ответ
	 */
	public function setAnswer(array $answer = Array())
	{
		$this->answer =   $answer;
	}

	/**
	 * Отдает ответ
	 * @return array ответ
	 */
	public function getAnswer()
	{
		return $this->answer;
	}

	/**
	 * Устанавливает настроки просмотрщика
	 * @param array $viewerConfig настроки просмотрщика
	 */
	public function setConfig(array $viewerConfig = Array())
	{
		$this->viewerConfig =   $viewerConfig;
	}


	/**
	 * Умная сортировка
	 * @param array $v1 элемент массива
	 * @param array $v2 элемент массива
	 *
	 * @return int порядок
	 */
	protected function callbackSchemaSort($v1, $v2): int
	{
		if (!isset($v1[self::$mode]['order'])) {
			$v1[self::$mode]['order'] = 0;
		}
		if (!isset($v2[self::$mode]['order'])) {
			$v2[self::$mode]['order'] = 0;
		}
		if ($v1[self::$mode]['order'] === $v2[self::$mode]['order']) {
			return 0;
		}
		return ($v1[self::$mode]['order'] < $v2[self::$mode]['order'])? -1: 1;
	}

	/**
	 * Отдает подключает js и css шаблон просмотрщика
	 * @return string шаблон просмотрщика
	 */
	protected function getViewerTemplate()
	{
		$js         =   isset($this->viewerConfig['js'])        ?   $this->viewerConfig['js']   :   Array();
		$css        =   isset($this->viewerConfig['css'])       ?   $this->viewerConfig['css']   :   Array();
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
		if(count($this->answer) === 0) {
			$template = $this->viewerConfig['templateNoData'];
		} else {
			$template = $this->viewerConfig['template'];
		}
		return self::checkTemplate($template);
	}

	/**
	 * Проверяет шаблон
	 *
	 * @param string $template шаблоно
	 *
	 * @return string
	 */
	private static function checkTemplate($template)
	{
		if (!file_exists($template)) {
			if (file_exists(core::getDR(true) . $template)) {
					return core::getDR() . $template;
			} elseif (file_exists(core::getDR(true) . $template . '.tpl')) {
					return core::getDR() . $template . '.tpl';
				} elseif (file_exists(self::getTemplate($template, __DIR__))) {
					return  self::getTemplate($template, __DIR__);
				} elseif (file_exists(self::getTemplate($template . '.tpl', __DIR__))) {
					return self::getTemplate($template . '.tpl', __DIR__);
				} elseif (file_exists(self::$config['controller']::getTemplate($template))) {
					return self::$config['controller']::getTemplate($template);
				} elseif (file_exists(self::$config['controller']::getTemplate($template . '.tpl', __DIR__))) {
					return self::$config['controller']::getTemplate($template . '.tpl');
				} elseif (file_exists(core::getDR(true) . self::getTemplate($template, __DIR__))) {
					return core::getDR(true) . self::getTemplate($template, __DIR__);
				} elseif (file_exists(core::getDR(true) . self::getTemplate($template . '.tpl', __DIR__))) {
					return  core::getDR(true) . self::getTemplate($template . '.tpl', __DIR__);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($template))) {
					return core::getDR(true) . self::$config['controller']::getTemplate($template);
				} elseif (file_exists(core::getDR(true) . self::$config['controller']::getTemplate($template . '.tpl', __DIR__))) {
					return core::getDR(true) . self::$config['controller']::getTemplate($template . '.tpl');
				} else {
					die('Не верный путь к шаблону формы' . $template);
				}
		}
		return $template;
	}

    public function init()
    {
        $config = self::$config;
        unset($config['viewer']);
        $this->viewerConfig = array_merge($this->viewerConfig, $config);
        $this->schemaField                =  $this->viewerConfig['field'];
    }

    /**
     *  Заполняет поля для запроса
     */
    protected function fillField()
    {
        $this->field[] =    'id';
        foreach ($this->schemaField as $key => $field) {
            if (
                (
                    isset($field['view'])
                    && $field['view'] === false
                )
                || (
                    isset($field[self::$mode]['view'])
                    && $field[self::$mode]['view'] === false
                )
            ) {
                unset($this->schemaField[$key]);
                continue;
            }
            $this->field[] = $field['field'];
        }
    }

    /**
     * Подготавливает условие
     * @param array $where условие
     * @return array условие
     */
    protected function preparationWhere(array $where = Array()): array
    {
        if (isset($this->viewerConfig['where'])) {
            $where    = array_merge($where, $this->viewerConfig['where']);
        }
        if (
            isset($this->viewerConfig['parent'], $this->viewerConfig['parent_field'])
            && false !== $this->viewerConfig['parent']
        ) {
            $where    = array_merge(
                $where,
                Array(
                    $this->viewerConfig['parent_field']    =>  $this->viewerConfig['parent'],
                )
            );
        }
        return $where;
    }

    /**
     * Отдает родительский ID
     * @return bool|int Родительский ID
     */
    protected function getParent()
    {
        if (isset($this->viewerConfig['parent'])) {
            return $this->viewerConfig['parent'];
        }
        if (count(self::$subURL) >= 3) {
            return   (int)self::$subURL[0];
        }
        if (isset($this->viewerConfig['parent_field'])) {
            return  0;
        }
        return false;
    }

    /**
     * Отдает ID
     * @return int ID
     */
    protected function getID(): int
    {
        if (isset($this->viewerConfig['id'])) {
            return (int)$this->viewerConfig['id'];
        }
        if (count(self::$subURL) >= 2) {
            return (int)end(self::$subURL);
        }
        return 0;
    }

    /**
     * Отдает текущую страницу
     * @return int текущая страница
     */
    protected  function getPageNow(): int
    {
        if (isset($this->viewerConfig['page'])) {
            return $this->viewerConfig['page'];
        }
        if (count(self::$subURL) >= 2) {
            return   (int)end(self::$subURL);
        }
        return 1;

    }

    /**
     * Отдает всего на странице
     * @return int всего на странице
     */
    protected function getOnPage()
    {
        $paginationKey   =   'pagination' . self::$config['controller']::getPageURL() . self::$mode;
        if (isset($_GET['onPage'])) {
            setcookie($paginationKey, $_GET['onPage'], time() + 2592000, '/');
            return (int)$_GET['onPage'];
        }
        if (isset($_COOKIE[$paginationKey])) {
            return  (int)$_COOKIE[$paginationKey];
        }
        if (isset($this->viewerConfig['onPage'])) {
            return  (int)$this->viewerConfig['onPage'];
        }
        return 10;
    }
}