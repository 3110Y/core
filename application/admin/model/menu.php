<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 15.02.18
 * Time: 19:42
 */

namespace application\admin\model;

use core\{
    application\AClass, registry\registry, router\URL
};



class menu extends AClass
{
    private $table;

    private $order;

    private $parent;

    private $url;

    private $icon;

    private $name;

    public function __construct(string $table,  array $config = Array())
    {
        $this->table    = $table;
        $this->order    = $config['order']  ??  'order_in_menu';
        $this->parent   = $config['parent'] ??  'parent_id';
        $this->url      = $config['url']    ??  'url';
        $this->icon     = $config['icon']   ??  'icon';
        $this->name     = $config['name']   ??  'name';
    }

    /**
     * @param string $parentURL
     * @param int $parentID
     * @return array
     */
    public function getMenu(string $parentURL = '/', int $parentID = 0)  :   array
    {
        var_dump(URL::getURL(), URL::getURLPointerNow(), URL::getFullURLPointerNow());
        /** @var \core\PDO\PDO $db */
        $db = registry::get('db');
        $where  =   [
            'parent_id' => $parentID,
            '`order_in_menu` != 0',
            '`status` = 1'
        ];

        $rows           =   Array();
        $parentClass    =   '';
        $parentURL      =   $parentURL !== '/'   ?   $parentURL . '/'  :   $parentURL;
        $query          =   $db->select($this->table, '*', $where, $this->order);
        if ($query->rowCount() > 0) {
            while ($row =  $query->fetch()) {
                $class = '';
                $URL = $row[$this->url] == '/' ? $parentURL : $parentURL . $row[$this->url];
                if ($row['url'] == self::$page['url'] && $row['parent_id'] == self::$page['parent_id']) {
                    $class          .=  'active ';
                    $parentClass    =   'open ';
                }

            }
        } else {

        }
    }
}