<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:01
 */

namespace core\authentication;


abstract class AAuthentication
{
    /**
     * @var \core\PDO\PDO
     */
    protected $db;


    /**
     * @var array
     */
    protected $config = Array(
        'user'      => 'core_user',
        'group'     => 'core_user_group',
        'rules'     => 'core_rules',
        'object'    => 'core_rules_objects',
        'uid'       => 'uid',
        'gid'       => 'gid',
        'hash'      => 'hash',
        'alg'       => 'sha512',
    );


    /**
     * component constructor.
     * @param \core\PDO\PDO $db
     * @param array $config
     */
    public function __construct($db, array $config = Array())
    {
        if (isset($config['user'])) {
            $this->config['user'] = $config['user'];
        }
        if (isset($config['group'])) {
            $this->config['group'] = $config['group'];
        }
        if (isset($config['rules'])) {
            $this->config['rules'] = $config['rules'];
        }
        if (isset($config['object'])) {
            $this->config['object'] = $config['object'];
        }
        if (isset($config['uid'])) {
            $this->config['uid'] = $config['uid'];
        }
        if (isset($config['gid'])) {
            $this->config['gid'] = $config['gid'];
        }
        if (isset($config['hash'])) {
            $this->config['hash'] = $config['hash'];
        }
        if (isset($config['alg'])) {
            $this->config['alg'] = $config['alg'];
        }
        $this->db = $db;
    }

}