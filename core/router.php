<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 12:21
 */

namespace core;


/**
 * Class router Роутер ядра
 * @package core
 */
class router
{
    /**
     * @var array структура
     */
    private $structure;
    /**
     * @var array URL
     */
    protected $URL = Array();
    /**
     * @var array URL приложения
     */
    protected $urlApp = '';
    /**
     * @var array путь
     */
    protected $path = Array();
    /**
     * @var array $application приложение
     */
    protected $application = Array();


    /**
     * router constructor.
     * @param array $structure структура
     */
    public function __construct($structure = Array())
    {
        $this->structure    =   $structure;
        $URL                =   explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $URL[0] = '/';
        $urlFirst   =    $URL;
        $urlSecond  =    $URL;
        array_shift($urlFirst);
        if(count($urlFirst) === 1) {
            $urlFirst[] = '';
        }
        $this->application  =   $this->setURL($urlFirst);
        if (empty($this->application)) {
            $this->application  =   $this->setURL($urlSecond);
        } else {
            $this->URL[0]   =   '/' . $this->URL[0];
        }
        if (empty($this->application)) {
            //TODO: нет приложения
            die('нет приложения');
        }
    }

    /**
     * Устанавливает URL структуы
     * @param array $url URL
     * @return array приложение
     */
    private function setURL($url)
    {
        foreach ($this->structure as $item) {
            if ($item['url'] === $url[0]) {
                $this->URL = $url;
                return $item;
            }
        }
        return Array();
    }

    /**
     * Запускает роутинг
     * @return mixed|resource роутер
     */
    public function run()
    {
        if (!empty($this->application)) {
            $namespace  =   'application\\' . $this->application['path'];
            core::getInstance()->addNamespace($namespace, $namespace);
            $application = $namespace . '\router';
            $router = new $application($this->URL, $this->application);
            $router->run();
            return $router->render();
        }

        return 'Нет приложения';
    }
}
