<?php

/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:40
 */

namespace core\connectors\app;

/**
 * Abstract class router
 * @package core\connectors\app
 */
abstract class router
{
    protected $url = Array();

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
     * @param array $structure структура приложения
     * @return array текущая страница
     */
    public function getSelectedPage(array $structure = Array())
    {
        $this->url    =   parse_url($_SERVER['REQUEST_URI']);
        for ($i = 0, $iMax = count($structure); $i < $iMax; $i++) {
            if ($structure[$i]['url'] === $this->url['path']) {
                return $structure[$i];
            }
            if ($structure[$i]['error']) {
                $pageError = $structure[$i];
            }
        }
        return $pageError;
    }



}