<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 2.5.2017
 * Time: 18:15
 */

namespace Core\_application\model;

use Core\{
    _registry\registry,
    URI\URL,
    _helper\lowToUpper
};
/**
 * Class AMenu
 * @package core\application
 */
abstract class AMenu
{

    use lowToUpper;

    /**
     * @var string
     */
    private $table;

    /**
     * @var mixed|string
     */
    private $order;

    /**
     * @var string
     */
    private $parent;

    /**
     * @var mixed|string
     */
    private $url;

    /**
     * @var array|mixed
     */
    private $where;

    /**
     * menu constructor.
     * @param string $table таблица
     * @param array $config конфигурация
     * $config['order']     =   Сортировка
     * $config['parent']    =   Имя Колонки Родительского ID
     * $config['url']       =   URL Имя Колонки
     * $config['where']     =   Условие по умолчанию
     */
    public function __construct(string $table,  array $config = Array())
    {
        $this->table    = $table;
        $this->order    = $config['order']      ??  'order_in_menu';
        $this->parent   = $config['parent']     ??  'parent_id';
        $this->url      = $config['url']        ??  'url';
        $this->where    = $config['where']      ??  [
                [
                    'field' =>  'order_in_menu',
                    'cond'  =>  '!=',
                    'value' =>  0
                ]
            ];
    }

    /**
     * Генерирует меню
     * @param int $pointerNow Указатель сейчас
     * @param string $parentURL Родительский URL
     * @param int $parentID Родительский ID
     * @return array меню
     */
    public function getMenu(int $pointerNow = 0, string $parentURL = '/', int $parentID = 0)  :   array
    {

        /** @var \Core\PDO\PDO $db */
        $db                         =   registry::get('db');
        $URLNow                     =   URL::getURLPointer($pointerNow);
        $parentURL                  =   $parentURL !== '/'   ?   $parentURL . '/'  :   $parentURL;
        $menu                       =   [];
        $this->where[$this->parent] =   $parentID;
        $query                      =   $db->select($this->table, '*', $this->where, $this->order);
        if ($query->rowCount() > 0) {
            ++$pointerNow;
            while ($row =  $query->fetch()) {
                $item               =   $row;
                $item[$this->url]   =   $item[$this->url] !== '/'   ?   $item[$this->url]   :  '';
                $item[$this->url]   =   $parentURL . $item[$this->url];
                $item['active']     =   $URLNow === $item[$this->url];
                $item['sub']        =   $this->getMenu($pointerNow, $item[$this->url], $row['id']);
                $item['count_sub']  =   \count($item['sub']) !== 0;
                $menu[] =   $item;
            }
        }
        return $menu;
    }
}