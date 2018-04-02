<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 29.03.18
 * Time: 13:21
 */

namespace core\callTracking;


use core\registry\registry;


/**
 * Class callOrder
 * @package core\callTracking
 */
class callOrder
{
    /**
     * @var string
     */
    private $number =   '';

    /**
     * @var int
     */
    private $visit_id   =   0;


    /**
     * callOrder constructor.
     * @param string $number
     */
    public function __construct($number)
    {
        $this->visit_id   =   visit::getID();
        $this->number     =   $number;
    }

    /**
     * Сохранение
     */
    public function save(): void
    {
        /** @var \core\PDO\PDO $db */
        $db =   registry::get('db');
        $value  =   [
            'visit_id'      =>  $this->visit_id,
            'number'        =>  $this->number
        ];
        $db->inset('callTracking_call_order', $value);
    }

}
