<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 12:21
 */

namespace core;
use core\component\application as application;


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
     * @var string шаблон
     */
    protected static $template = '';

    /**
     * router constructor.
     * @param array $structure структура
     */
    public function __construct($structure = Array())
    {
        $this->structure    =   $structure;
        $site               =   '';
        if (isset($_SERVER['SHELL']) && isset($argv)) {
            $URL    =   $argv;
            $URL[0] = '/';
        } else {
            $URL    =   explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $site   =   $_SERVER['HTTP_HOST'];
        }
        $urlFirst   =    $URL;
        $urlSecond  =    $URL;
        array_shift($urlFirst);
        if(count($urlFirst) === 1) {
            $urlFirst[] = '';
        }
        $this->application  =   $this->setURL($urlFirst, $site);
        if (empty($this->application)) {
            $this->application  =   $this->setURL($urlSecond, $site);
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
     * @param string $site сайт
     * @return array приложение
     */
    private function setURL($url, $site = '')
    {
        foreach ($this->structure as $item) {
            if (!isset($item['url'])) {
                $item['url']    =   $item['path'];
            }
            $replace    =   Array(
                '*'  =>  '([\w]+)$',
                '/'  =>  '\/',
            );
            $item['site']   =  '/^' . strtr($item['site'], $replace) . '$/i';
            preg_match($item['site'], $site, $output);
            if (($item['url'] === $url[0] || ($item['url'] == '/' && $url[0] == '')) && isset($output[0])) {
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
            $handler    =   $this->application['handler'];
            $handler    =   "\\core\\component\\application\\handler\\{$handler}\\component";
            if (new $handler() instanceof application\IHandler) {
                return $handler::factory($this->application, $this->URL);
            }
        }
        return 'Нет приложения';
    }
}
