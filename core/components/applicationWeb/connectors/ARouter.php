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
 * @package core\connectors\app
 */
abstract class ARouter
{
    /**
     * @var array URL
     */
    protected $url = Array();
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


        foreach ($this->structure  as $item) {
            if ($countURL === $parent_id + 1) {
                if ($item['url'] === $this->url[$parent_id + 1] && $item['parent_id'] === $parent_id) {
                    return $item;
                }
            } else {
                if ($item['url'] === $this->url[$parent_id + 1] && $item['parent_id'] === $parent_id) {
                    return $this->getSelectedPage(++$parent_id);
                }
            }
        }
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
     * @param int $urlSectionID ID рвздела URL
     * @return array текущая страница
     */
    private function getPage($parentID = 0, $urlSectionID = 0)
    {
        foreach ($this->structure as $item) {
            if ($item['url'] === $this->url[1] && $item['parent_id'] === $parent_id) {
                return $item;
            }
        }
    }



}