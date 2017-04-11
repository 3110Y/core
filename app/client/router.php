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
     * @var array  текущая страница
     */
    private $page = Array();
    /**
     * @var array структура контента
     */
    private $content = Array();
    /**
     * @var string шаблон
     */
    private $template = '';


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
            Array(
                'id'                =>  3,
                'parent_id'         =>  0,
                'name'              =>  '3',
                'url'               =>  '3',
                'meta_title'        =>  '3',
                'meta_keywords'     =>  '3, Тест',
                'meta_description'  =>  '3',
                'controller'        =>  controllers\front::class,
                'template'          =>  'front',
                'home'              =>  1,
                'error'             =>  0,
            ),
            Array(
                'id'                =>  4,
                'parent_id'         =>  3,
                'name'              =>  '4',
                'url'               =>  '4',
                'meta_title'        =>  '4',
                'meta_keywords'     =>  '4, Тест',
                'meta_description'  =>  '4',
                'controller'        =>  controllers\front::class,
                'template'          =>  'front',
                'home'              =>  1,
                'error'             =>  0,
            ),
        );
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function run()
    {
        $this->page         = $this->getSelectedPage();
        $controller         = new $this->page['controller']();
        $controller->setPage($this->page);
        $controller->setURL($this->url);
        $controller->Init();
        $this->content      = $controller->getContent();
        $this->template     = $controller->getTemplate();
        return $this;
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function render()
    {
        $components  =   core::getComponents('simpleView');
        $components->setTemplate($this->template);
        $components->setData($this->content);
        $components->render();
        return $components->get();
    }
}
