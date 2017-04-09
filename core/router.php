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
     * @var array $structure структура
     */
    private $structure;
    /**
     * @var array $url URL
     */
    protected $url = Array();
    /**
     * @var array $path путь
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
        $this->url          =   explode('/', $uri['path']);
        unset($this->url[0]);
        sort($this->url);
        if (count($this->url) === 1 && $this->url[0] === '') {
            $this->url[0] = '/';
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
        if (empty($this->application)) {
            return 'Нет приложения';
        } else {
            $application = '\app\\' . $this->application['path'] . '\\router';
            $router = new $application($this->url);
            $router->run();
            return $router->render();
        }
    }
}
