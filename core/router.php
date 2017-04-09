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
    protected $url = Array();


    /**
     * router constructor.
     * @param array $structure структура
     */
    public function __construct($structure = Array())
    {
        $this->structure    =   $structure;
        $this->url  =   parse_url($_SERVER['REQUEST_URI']);
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
