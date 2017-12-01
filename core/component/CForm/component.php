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
     * @var array ответ
     */
    private $answer         =   Array();

    /**
     * @var array просмотрщик
     */
    private  $viewer        =   Array();


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
        var_dump(parent::$id !== '0', parent::$id !== 0, (int)parent::$id == 0, isset($config['viewer'][parent::$mode]));
        if (parent::$id !== '0' && parent::$id !== 0 && (int)parent::$id == 0 ) {
            parent::$isWork = false;
        } elseif (isset($config['viewer'][parent::$mode])) {
            $this->viewer = $config['viewer'][parent::$mode];
        }  elseif (parent::$mode == 'api') {
            $this->viewer = Array(
                'type' => 'api'
            );
        } else {
            parent::$isWork = false;
        }
        die();
    }

    /**
     *  Запуск
     */
    public function run()
    {
        if (parent::$isWork && isset($this->viewer['type'])) {
            $viewer =   $this->viewer['type'];
            $viewer = "\core\component\CForm\\viewer\{$viewer}\component";
            /** @var \core\component\CForm2\viewer\listing\component $viewerComponent */
            $viewerComponent  =   new $viewer();
            $viewerComponent->setConfig($this->viewer);
            $viewerComponent->setAnswer($this->answer);
            $viewerComponent->init();
            $viewerComponent->run();
            $this->answer   =   $viewerComponent->getAnswer();
        }
    }

    /**
     * Возвращяет массив для ответа
     * @return mixed|bool|array массив для ответа
     */
    public  function getIncomingArray()
    {
        if (parent::$isWork) {
            echo 'Работаем';
        } else {

        }

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
    
}