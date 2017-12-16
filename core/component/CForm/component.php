<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 30.11.2017
 * Time: 16:27
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
     * @const float Версия
     */
    const VERSION   =   2.0;

    /**
     * @var array массив для ответа
     */
    private $incomingArray;

    /**
     * @var string ключ массива для ответа
     */
    private $incomingKey;

    /**
     * @var mixed ответ
     */
    private $answer;


    /**
     * Устанавливает массив для ответа и его ключ
     * component constructor.
     *
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
        if (!isset($config['controller'])) {
            die('Не указан контроллер');
        }
        if (!isset($config['db'])) {
            die('Нет подключения к БД');
        }
        if (!isset($config['table'])) {
            die('Не указана таблица');
        }
        if (!isset($config['mode'])) {
            die('Не указан режим просмотрщика по умолчанию');
        }
        if (!isset($config['viewer'])) {
            die('Не указаны просмотрщики');
        }

        parent::$controller         =   $config['controller'];
        parent::$db                 =   $config['db'];
        parent::$table              =   $config['table'];
        parent::$caption            =   $config['caption'];
        parent::$mode               =   $config['mode'];
        parent::$subURL             =   parent::$controller::getSubURL();
        if (isset(parent::$subURL[0])) {
            parent::$id = parent::$subURL[0];
            parent::$subURLNow++;
        }
        if (isset(parent::$subURL[1])) {
            parent::$mode = parent::$subURL[1];
            parent::$subURLNow++;
        }
        if (parent::$id !== '0' && parent::$id !== 0 && (int)parent::$id == 0 ) {
            parent::$isWork = false;
        } elseif (isset($config['viewer'][parent::$mode])) {
            self::$viewerConfig = $config['viewer'][parent::$mode];
        }  elseif (parent::$mode == 'api') {
            self::$viewerConfig = Array(
                'type' => 'api'
            );
        } else {
            parent::$isWork = false;
        }
        if (parent::$isWork && !isset(self::$viewerConfig['field']) && isset($config['field'])) {
            self::$viewerConfig['field'] = $config['field'];
        }
    }

    /**
     *  Запуск
     */
    public function run()
    {
        if (!isset(self::$viewerConfig['type'])) {
            self::$viewerConfig['type'] = parent::$mode;
        }
        if (parent::$isWork) {
            $viewerName =   self::$viewerConfig['type'];
            unset(self::$viewerConfig['type']);
            $viewer =   "core\component\CForm\\viewer\\{$viewerName}\component";
            if (class_exists($viewer)) {
                if (isset(self::$viewerConfig['caption'])) {
                    parent::$caption            =   self::$viewerConfig['caption'];
                }
                if (isset(self::$viewerConfig['field']) && !empty(self::$viewerConfig['field'])) {
                    $this->preparationField();
                }
                if (isset(self::$viewerConfig['button']) && !empty(self::$viewerConfig['button'])) {
                    $this->preparationButton();
                }

                /** @var \core\component\CForm\viewer\UKListing\component $viewerComponent */
                $viewerComponent = new $viewer();
                $viewerComponent->init();
                $viewerComponent->run();
                $this->answer = $viewerComponent->getAnswer();
            }
        }
    }

    /**
     * Возвращяет массив для ответа
     * @return mixed|bool|array массив для ответа
     */
    public  function getIncomingArray()
    {
        if (!parent::$isWork) {
            return false;
        }
        /** @var \core\component\application\handler\Web\AApplication $controller */
        $controller = parent::$controller ;
        if ($controller->isAjaxRequest()) {
            return $this->answer;
        }
        $this->incomingArray[$this->incomingKey] = $this->answer;
        return $this->incomingArray;
    }

    /**
     * Подготавливет кнопки
     *
     */
    private function preparationButton()
    {
        foreach (self::$viewerConfig['button'] as $k => $b) {
            $buttons = Array();
            foreach (self::$viewerConfig['button'][$k] as $key => $button) {
                if (isset($button[self::$mode]) && !empty($button[self::$mode])) {
                    foreach ($button[self::$mode] as $valueName => $value) {
                        $button[$valueName] = $value;
                    }
                }
                unset($button[self::$mode]);
                if (!isset($button['view']) || $button['view'] === true) {
                    if (!isset($button['order'])) {
                        $button['order'] = $key;
                    }
                    $buttons[] = $button;
                }
            }
            usort($buttons, Array($this, 'callbackSchemaSort'));
            self::$viewerConfig['button'][$k] = $buttons;
        }

    }

    /**
     * Подготавливет поля
     *
     */
    private function preparationField()
    {
        $fields = Array();
        $i = 0 ;
        foreach (self::$viewerConfig['field'] as $key => $field) {
            if (isset($field[self::$mode]) && !empty($field[self::$mode])) {
                foreach ($field[self::$mode] as $valueName => $value) {
                    $field[$valueName] = $value;
                }
            }
            unset($field[self::$mode]);
            if (!isset($field['view']) || $field['view'] === true) {
                 if (!isset($field['order'])) {
                     $field['order'] = $i;
                 }
                $fields[] = $field;
            }
            $i++;
        }
        usort($fields, Array($this, 'callbackSchemaSort'));
        self::$viewerConfig['field'] = $fields;
    }

    /**
     * Сортировщик
     *
     * @param array $v1
     * @param array $v2
     * @return int
     */
    protected function callbackSchemaSort($v1, $v2): int
    {
        if (!isset($v1['order'])) {
            $v1['order'] = 0;
        }
        if (!isset($v2['order'])) {
            $v2['order'] = 0;
        }
        if ($v1['order'] === $v2['order']) {
            return 0;
        }
        return ($v1['order'] < $v2['order'])? -1: 1;
    }

}