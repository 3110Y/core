<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\components\applicationWeb\connectors;


/**
 * Abstract class router
 * Коннектор Роутера Web приложения
 * @package core\connectors\app
 */
abstract class ARouter
{
    /**
     * @var array URL
     */
    protected $URL = Array();
    /**
     * @var array структура приложения
     */
    protected $structure;
    /**
     * @var array текущая страница
     */
    protected $page = Array();
    /**
     * @var array страница для ошибок
     */
    protected $pageError = Array();
    /**
     * @var array структура контента
     */
    protected $content = Array();
    /**
     * @var string шаблон
     */
    protected $template = '';
    /**
     * @var mixed|null|object реестр
     */
    protected $registry = array();
    /**
     * @var array  приложение
     */
    protected $application = Array();


    /**
     * отдает тему
     * @param string $template шаблон
     * @return string шаблон
     */
    public function getTemplate($template = '')
    {
        return "/app/{$this->application['path']}/theme/{$this->application['theme']}/{$template}";
    }

    /**
     * задает ключь b значение реестра
     * @param string $key ключ
     * @param mixed|string|object $class класс
     * @return boolean
     */
    public function set($key, $class)
    {
        if (isset($this->registry[$key])) {
            return false;
        }
        return $this->registry[$key] = $class;
    }

    /**
     * Отдает значение ключа реестра
     * @param string $key ключ
     * @return mixed|null|object рендер
     */
    public function get($key)
    {
        //TODO: обработка ошибок
        if (isset($this->registry[$key])) {
            return $this->registry[$key];
        }
        return false;
    }

    /**
     * Отдает Верстку
     * @return string
     */
    public function render()
    {
        return $this->content;
    }
    /**
     * Задает текущую страницу и страницу Ошибок
     */
    public function selectPage()
    {
        $this->pageError    = $this->getPageError();
        $this->page         = $this->getPage();
    }
    /**
     * Отдает страницу Ошибок
     * @return array
     */
    private function getPageError()
    {
        foreach ($this->structure as $item) {
            if ($item['error']) {
                return $item;
            }
        }
        return $this->structure[0];
    }
    /**
     * Отдает текущую Ошибок
     * @param int $parentID уровень страницы
     * @return array текущая страница
     */
    private function getPage($parentID = 0)
    {
        foreach ($this->structure as $item) {
            if (
                $item['parent_id'] === $parentID
                && (
                    $item['url'] === $this->URL[$parentID + 1]
                    || (
                        $item['url'] == '/'
                        && $this->URL[$parentID + 1] === ""
                    )
                )
            ) {
                $controller     =   $item['controller'];
                $countSubURL    =   $controller::$countSubURL;
                if (
                    $parentID + 1 == (count($this->URL) - 1)
                    || (
                        $countSubURL === false
                        || $countSubURL >= (count($this->URL) + ($parentID + 1))
                    )
                ) {
                    $url    =   '';
                    for ($i = 0, $iMax = ($parentID + 2); $i < $iMax; $i++) {
                        $url[]    =  $this->URL[$i];
                    }
                    $subURL   =   Array();
                    for ($i = ($parentID + 2), $iMax = count($this->URL); $i < $iMax; $i++) {
                        $subURL[] = $this->URL[$i];
                    }
                    $controller::setPageURL(implode('/', $url));
                    $controller::setSubURL($subURL);
                    return $item;
                } else {
                    return self::getPage(($parentID + 1));
                }
            }

        }
        return $this->pageError;
    }



}