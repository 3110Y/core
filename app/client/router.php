<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace app\client;

use core\components\applicationWeb\connectors;
use core\core;


/**
 * Class router
 * Роутер приложения
 * @package app
 */
final class router extends connectors\ARouter implements connectors\IRouter
{
    /**
     * @var array структура приложения
     */
    private $structure;
    /**
     * @var array  текущая страница
     */
    private $page = Array();
    /**
     * @var array структура контента
     */
    private $content = Array();


    /**
     * router constructor.
     * @param array $url URL
     */
    public function __construct($url)
    {
        $this->url      =  $url;
        $this->structure = Array(
            Array(
                'id'                =>  1,
                'parent_id'         =>  0,
                'name'              =>  'Главная',
                'url'               =>  '/',
                'meta_title'        =>  'Главная',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  'Главная Тест',
                'controller'        =>  controllers\front::class,
                'template'          =>  'front',
                'home'              =>  1,
                'error'             =>  0,
            ),
            Array(
                'id'                =>  2,
                'parent_id'         =>  0,
                'name'              =>  '404',
                'url'               =>  '404',
                'meta_title'        =>  '404',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  '404 Тест',
                'controller'        =>  controllers\error::class,
                'template'          =>  '404',
                'home'              =>  0,
                'error'             =>  1,
            ),
        );
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function run()
    {
        $this->page         = $this->getSelectedPage($this->structure);
        $controller         = new $this->page['controller']($this->page, $this->url);
        $this->content      = $controller->getContent();
        return $this;
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function render()
    {

        $content    =   Array();
        foreach ($this->content as $key => $value) {
            $components  =   core::getComponents('simpleView');
            $components->setTemplate($key);
            $components->setData($value);
            $components->render();
            $content[]    =   $components->get();
        }
        return implode('', $content);
    }
}
