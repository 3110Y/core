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
     * @var array URL Страниц
     */
    protected $urlPage = Array();
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
        $uri                =   parse_url($_SERVER['REQUEST_URI']);
        $url                =   explode('/', trim($uri['path'], '/'));
        if ($url[0] === '') {
            $url[0] = '/';
        }
        $this->setURL($url);
        if (empty($this->application)) {
            array_unshift($url, '/');
            $this->setURL($url);
        }
    }

    /**
     * Устанавливает URL Страниц, URL приложения, структуру
     * @param array $url URL
     */
    private function setURL($url)
    {
        foreach ($this->structure as $item) {
            if ($item['url'] === $url[0]) {
                $this->application = $item;
                $this->urlApp = array_shift($url);
                $this->urlPage = $url;
                return;
            }
        }
    }

    /**
     * Запускает роутинг
     * @return mixed|resource роутер
     */
    public function run()
    {
        if (!empty($this->application)) {
            $application = '\app\\' . $this->application['path'] . '\\router';
            $router = new $application($this->urlApp, $this->urlPage);
            $router->run();
            return $router->render();
        }

        return 'Нет приложения';
    }
}
