<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 14:39
 */

namespace app\admin;

use core\components\applicationWeb\connectors as applicationWebConnectors;
use core\core;


/**
 * Class router
 * Роутер приложения
 * @package app
 */
final class router extends applicationWebConnectors\ARouter implements applicationWebConnectors\IRouter
{

    private static $config = Array(
        'driver'            =>  'mysql',
        'host'              =>  '127.0.0.1',
        'port'              =>  '3306',
        'db'                =>  'core',
        'name'              =>  'root',
        'pass'              =>  '',
        'character'         =>  'UTF8',
    );

    /**
     * router constructor.
     * @param string $URL URL приложения
     * @param array $application данные приложения
     */
    public function __construct($URL, $application)
    {
        $this->URL                  =  $URL;
        $this->application          =  $application;
        $this->set('view', core::getComponents('simpleView'));
        $this->get('view')->setExtension('tpl');
        $this->set('db', core::getComponents('PDO',true)::getInstance(self::$config));
        $this->set('GF', core::getComponents('generatorForm',true));
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
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1
            ),
            Array(
                'id'                =>  5,
                'parent_id'         =>  0,
                'name'              =>  'Пользователи',
                'url'               =>  'users',
                'meta_title'        =>  'Пользователи',
                'meta_keywords'     =>  'Пользователи',
                'meta_description'  =>  'Пользователи',
                'controller'        =>  controllers\users::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  6,
                'parent_id'         =>  0,
                'name'              =>  'Страницы',
                'url'               =>  'pages',
                'meta_title'        =>  'Страницы',
                'meta_keywords'     =>  'Страницы',
                'meta_description'  =>  'Страницы',
                'controller'        =>  controllers\pages::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  7,
                'parent_id'         =>  0,
                'name'              =>  'Новости',
                'url'               =>  'news',
                'meta_title'        =>  'Новости',
                'meta_keywords'     =>  'Новости',
                'meta_description'  =>  'Новости',
                'controller'        =>  controllers\news::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  8,
                'parent_id'         =>  0,
                'name'              =>  'Акции',
                'url'               =>  'promotions',
                'meta_title'        =>  'Акции',
                'meta_keywords'     =>  'Акции',
                'meta_description'  =>  'Акции',
                'controller'        =>  controllers\promotions::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  9,
                'parent_id'         =>  0,
                'name'              =>  'Услуги',
                'url'               =>  'services',
                'meta_title'        =>  'Услуги',
                'meta_keywords'     =>  'Услуги',
                'meta_description'  =>  'Услуги',
                'controller'        =>  controllers\services::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  10,
                'parent_id'         =>  0,
                'name'              =>  'Отделы',
                'url'               =>  'departments',
                'meta_title'        =>  'Отделы',
                'meta_keywords'     =>  'Отделы',
                'meta_description'  =>  'Отделы',
                'controller'        =>  controllers\departments::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  11,
                'parent_id'         =>  0,
                'name'              =>  'Специалисты',
                'url'               =>  'specialists',
                'meta_title'        =>  'Специалисты',
                'meta_keywords'     =>  'Специалисты',
                'meta_description'  =>  'Специалисты',
                'controller'        =>  controllers\specialists::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  12,
                'parent_id'         =>  0,
                'name'              =>  'связка',
                'url'               =>  'bunch',
                'meta_title'        =>  'связка',
                'meta_keywords'     =>  'связка',
                'meta_description'  =>  'связка',
                'controller'        =>  controllers\bunch::class,
                'template'          =>  'default',
                'home'              =>  1,
                'error'             =>  0,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  1,
            ),
            Array(
                'id'                =>  13,
                'parent_id'         =>  0,
                'name'              =>  '404',
                'url'               =>  '404',
                'meta_title'        =>  '404',
                'meta_keywords'     =>  'Тест, Тест',
                'meta_description'  =>  '404 Тест',
                'controller'        =>  controllers\error::class,
                'template'          =>  'default',
                'home'              =>  0,
                'error'             =>  1,
                'icon'              =>  'zmdi zmdi-home',
                'view'              =>  0,
            ),
        );
    }

    public function generationMenu()
    {
        $structure  =   Array();
        foreach ($this->structure as $item) {
            if($item['view']) {
                //TODO: Костыль
                if($item['url'] == '/') {
                    $item['url'] = '';
                }
                $structure[] = Array(
                    'ACTIVE' => ($item['url'] == $this->page['url']) ? 'active' : '',
                    'URL' => $this->URL[0] . '/' . $item['url'],
                    'ICON' => $item['icon'],
                    'NAME' => $item['name'],
                );
            }
        }
        $tpl    =   "/app/{$this->application['path']}/theme/{$this->application['theme']}/menu.tpl";
        return $this->get('view')->loop('FOR', $structure, '' , $tpl);
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function run()
    {
        $this->selectPage();
        /** @property  \app\admin\controllers\front $controller */
        $controller         = new $this->page['controller']();
        $this->page['controller']::setPage($this->page);
        $this->page['controller']::setURL($this->URL);
        $this->page['controller']::setRouter($this);
        $controller->init();
        $this->content          =   $controller->getContent();
        $this->content['MENU']  =   $this->generationMenu();
        $this->content['JS']    =   $controller->getJS();
        $this->content['CSS']   =   $controller->getCSS();
        $this->content['THEME'] =   "/app/{$this->application['path']}/theme/{$this->application['theme']}/";
        $this->content['APP']   =   "/app/{$this->application['path']}/";

        $this->template         =   $controller->getTemplate();
        return $this;
    }

    /**
     * Запускает роутинг
     * @return router
     */
    public function render()
    {

        $this->get('view')->setTemplate($this->template);
        $this->get('view')->setData($this->content);
        $this->get('view')->run();
        return  $this->get('view')->get();
    }
}
