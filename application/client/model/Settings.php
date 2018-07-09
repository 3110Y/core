<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8.9.2017
 * Time: 17:17
 */

namespace application\client\model;

use \core\component\{
    application,
    registry\registry
};

class Settings extends application\AClass
{
    /** @var string */
    private const TABLE = 'client_settings';

    /** @var Settings[] */
    private static $instance = [];

    /** @var array */
    private $configuration;


    /**
     * @param string|null $table
     * @param int $rowId
     * @return Settings
     */
    public static function getInstance(?string $table = null, int $rowId = 1): Settings
    {
        if (null === $table) {
            $table = self::TABLE;
        }
        if (!isset(self::$instance[$table])) {
            self::$instance[$table] = new self($table, $rowId);
        }
        return self::$instance[$table];
    }

    /**
     * @param string $table
     * @param int $rowId
     */
    public function __construct(string $table, int $rowId)
    {
        /** @var \core\component\PDO\PDO $db */
        $db =   registry::get('db');
        $where = Array(
            'id' => $rowId
        );
        $this->configuration =   $db->selectRow($table, '*', $where);
    }

    /**
     * @param string $key
     * @return mixed|bool
     */
    public function getConfiguration(string $key)
    {
        return $this->configuration[$key] ?? false;
    }
}