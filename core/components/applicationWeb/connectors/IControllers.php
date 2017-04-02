<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:05
 */

namespace core\connectors\app;

/**
 * Interface IControllers
 * @package core\connectors\app
 */
interface IControllers
{
    /**
     * IControllers constructor.
     * @param array $page страница
     * @param array $url URL
     */
    public function __construct(array $page,  array $url);

    /**
     * Отдает структуру контента
     * @return array структура контента
     */
    public function getContent();
}