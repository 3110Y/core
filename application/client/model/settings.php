<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8.9.2017
 * Time: 17:17
 */

namespace application\client\model;

use \core\{
    application\model\AModel,
    registry\registry
};

class settings extends AModel
{
    /**
     * @var string
     */
    private static $table = 'client_settings';

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @var null
     */
    private static $configuration = null;


    /**
     * @param string $table
     * @return settings|null
     */
    public static function getInstance(string $table = '') {
        if ($table !== '') {
            self::$table = $table;
        }
        if (!isset(self::$instance) || self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * cart constructor.
     */
    public function __construct()
    {
        /** @var \core\PDO\PDO $db */
        $db =   registry::get('db');
        $where = Array(
            'id' => 1
        );
        self::$configuration =   $db->selectRow(self::$table, '*', $where);
    }

    public function getConfiguration($key)
    {
        return isset(self::$configuration[$key])    ?   self::$configuration[$key]  :   '';
    }
}