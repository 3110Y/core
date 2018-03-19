<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:03
 */

namespace core\component\authentication;


class authorization extends AAuthentication
{

    /**
     * Вход
     * @param string $login
     * @param string $password
     * @return bool
     */
    public  function login(string $login, string $password)
    {
        $where = Array(
            'login'     => $login,
            'password'  => hash('sha512', $password)
        );
        $select = $this->db->select($this->config['user'],'*', $where);
        if ($select->rowCount() > 0) {
            $row = $select->fetch();
            $time   =   time() + 2592000;
            setcookie($this->config['uid'], $row['id'], $time, '/');
            $hash = hash($this->config['alg'], $row['id'] . $row['password']);
            setcookie($this->config['hash'], $hash, $time, '/');
            $where = Array(
                'user_id'     => $row['id'],
            );
            $rows   =   $this->db->selectRows($this->config['group'],'*', $where);
            $group_id = Array( 0 );
            foreach ($rows as  $row) {
                $group_id[] = $row['group_id'];
            }
            setcookie('gid', implode(',', $group_id), $time, '/');
            return true;
        }
        return false;
    }

    /**
     * Проверка
     *
     * Внимание! Данная функция не подходит для проверки авторизации.
     * Только для проверки подлинности uid в куках.
     * Используйте после получения user->get() с проверкой на ненулевой user id
     *
     * @return bool
     */
    public  function check(): bool
    {
        if (!isset($_COOKIE[$this->config['uid']])) {
            return true;
        }
        $where = Array(
            'id'     => $_COOKIE[$this->config['uid']]
        );
        $select = $this->db->select($this->config['user'],'*', $where);
        if ($select->rowCount() < 1) {
            return false;
        }
        $row = $select->fetch();
        $hash = hash($this->config['alg'], $row['id'] . $row['password']);
        return $hash === $_COOKIE[$this->config['hash']];
    }

    /**
     * Выход
     */
    public  function logout()
    {
        $time   =   time() - 100;
        setcookie($this->config['uid'], 0, $time, '/');
        setcookie($this->config['gid'], 0, $time, '/');
        setcookie($this->config['hash'], '', $time, '/');
        unset($_COOKIE[$this->config['uid']], $_COOKIE[$this->config['gid']], $_COOKIE[$this->config['hash']]);
    }
}