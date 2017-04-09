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
     * router constructor.
     * @param array $structure структура
     */
    public function __construct($structure = Array())
    {
        $this->structure    =   $structure;
        $uri                =   parse_url($_SERVER['REQUEST_URI']);
        $this->url          =   explode('/', $uri['path']);
        echo '<pre>';
        die(var_dump($this->url));
    }

    public function run()
    {
        $router = new \app\client\router();
        $router->run();
        return $router->render();
    }
}
