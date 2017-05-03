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
        if (isset($_SERVER['SHELL']) && isset($argv)) {
            $URL    =   $argv;
            $URL[0] = '/';
        } else {
            $URL    =   explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
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
        if (!isset($item['url'])) {
            $item['url']    =   $item['path'];
        }
        foreach ($this->structure as $item) {
            if ($item['url'] === $url[0] || ($item['url'] == '/' && $url[0] == '')) {
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
            return $handler::factoty($this->application, $this->URL);
        }
        return 'Нет приложения';
    }
}
