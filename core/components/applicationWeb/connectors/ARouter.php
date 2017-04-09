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
     * @param int $parent_id уровень страницы
     * @return array текущая страница
     */
    public function getSelectedPage($parent_id = 0)
    {
        echo "<hr>";
        echo "parent_id {$parent_id}<br>";

        $pageError  =   Array();
        $countURL  = count($this->url);
        foreach ($this->structure  as $item) {

            $i = $parent_id + 1;
            echo " {$countURL} === {$i}<br>";
            echo "i {$i}<br>";
            echo '<pre>';
            print_r($this->url);
            echo '</pre>';
            echo '<br>';

            if ($countURL === $parent_id + 1 && isset($this->url[$parent_id + 1])) {
                echo '№1';
                echo "item['url'] {$item['url']}";
                var_dump($this->url[$parent_id + 1]);
                if ($item['url'] === $this->url[$parent_id + 1] && $item['parent_id'] === $parent_id) {
                    return $item;
                }
            } else {
                echo '№2';
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