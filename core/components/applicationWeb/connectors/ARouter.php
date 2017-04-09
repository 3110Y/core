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
     * Отдает Верстку
     * @return string
     */
    public function render()
    {
        return $this->content;
    }

    /**
     * Отдает текущую страницу
     * @return array текущая страница
     */
    public function getSelectedPage()
    {
        $pageError  =   Array();
        foreach ($this->structure  as $item) {
            if ($item['url'] === $this->url[1] && $item['parent_id'] === 0) {
                return $item;
            }
            if ($item['error']) {
                $pageError = $item;
            }
        }
        return $pageError;
    }



}