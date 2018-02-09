<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 27.12.2017
 * Time: 23:59
 */

namespace core\component\authentication;


class component extends AAuthentication
{


    /**
     * @var array
     */
    private $key = Array();

    private static $instance = Array();

    /**
     * component constructor.
     * @param \core\component\PDO\PDO $db
     * @param array $config
     */
    public function __construct( $db, array $config = Array())
    {
        parent::__construct($db, $config);
        $this->key = md5(implode(',', $this->config));
    }

    public function get(string $key)
    {
        if (!isset(self::$instance[$this->key . $key]) || self::$instance[$this->key . $key] == null) {
            $key    =   'core\component\authentication\\' . $key;
            self::$instance[$this->key . $key] = new $key($this->db, $this->config);
        }
        return self::$instance[$this->key . $key];
    }


}