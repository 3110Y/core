<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 12:21
 */

namespace core;
use core\application as application;


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
     * @param array $URL
     */
    public function __construct($structure = Array(), array $URL = Array())
    {
        $this->structure    =   $structure;
        $site               =   '';
        if (isset($_SERVER['HTTP_HOST'])) {
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
                '*'  =>  '([\w]+)',
                '/'  =>  '\/',
            );
            $item['site_regular']   =  '/^' . strtr($item['site'], $replace) . '$/i';
            preg_match($item['site_regular'], $site, $output);
            if (isset($output[0], $url[0]) && ($item['url'] === $url[0] || ($item['url'] == '/' && $url[0] == ''))) {
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
            return application\application::create($this->application, $this->URL);
        }
        return 'Нет приложения';
    }
}
