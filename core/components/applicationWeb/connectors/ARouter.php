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
     * Отдает текущую страницу
     * @param int $parent_id уровень страницы
     * @return array текущая страница
     */
    public function getSelectedPage($parent_id = 0)
    {
        $pageError  =   Array();
        $countURL  = count($this->url);
        echo '<br>';
        var_dump($parent_id);
        echo '<br>';
        var_dump($countURL);
        echo '<br>';
        echo '<pre>';
        var_dump($this->url);
        echo '</pre>';
        echo '<br>';
        echo '<hr>';
        echo '<br>';
        foreach ($this->structure as $item) {
            if ($item['url'] === $this->url[1] && $item['parent_id'] === $parent_id) {
                return $item;
            }
            if ($item['error']) {
                $pageError = $item;
            }
        }
        return $pageError;


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
            if ($item['error']) {
                $pageError = $item;
            }
        }
        return $pageError;
    }



}