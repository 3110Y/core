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
    protected $urlApp = Array();
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
        var_dump($url);
        die();



        unset($this->url[0]);
        sort($this->url);
        if (count($this->url) === 1 && $this->url[0] === '') {
            $this->url[0] = '/';
            $this->url[1] = '/';
        }
        foreach ($structure as $item) {
            if ($item['url'] === $this->url[0]) {
                $this->application = $item;
            }
        }
        if (empty($this->application)) {
            array_unshift($this->url, '/');
        }
        foreach ($structure as $item) {
            if ($item['url'] === $this->url[0]) {
                $this->application = $item;
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
            $router = new $application($this->url);
            $router->run();
            return $router->render();
        }

        return 'Нет приложения';
    }
}
