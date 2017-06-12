<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 20:41
 */

namespace core\component\authorization;

/**
 * Class component
 * @package core\component\authorization
 */
class component
{

    /**
     * @param \core\components\PDO\component $db ДБ
     * @param string $login логин
     * @param string $password пароль
     * @return bool
     */
    public static function authorization($db, string $login, string $password): bool
    {
        $where = Array(
            'login'     => $login,
            'password'  => hash('sha512', $password)
        );
        $select = $db->select('core_user','*', $where);
        if ($select->rowCount() > 0) {
            $row = $select->fetch();
            $time   =   time() + 2592000;
            setcookie('uid', $row['id'], $time);
            setcookie('gid', explode(',', $row['group_id']), $time);
            $hash = hash('sha512', $row['id'] . $row['group_id'] . $row['password']);
            setcookie('hash', $hash, $time);
            return true;
        }
        return false;
    }


    /**
     * Проверка
     * @param \core\components\PDO\component $db ДБ
     * @return bool
     */
    public static function check($db): bool
    {
        if (!isset($_COOKIE['uid'])) {
            return false;
        }
        $where = Array(
            'id'     => $_COOKIE['uid']
        );
        $select = $db->select('core_user','*', $where);
        if ($select->rowCount() < 1) {
            return false;
        }
        $row = $select->fetch();
        $hash = hash('sha512', $row['id'] . $row['group_id'] . $row['password']);
        return $hash === $_COOKIE['hash'];
    }

    /**
     * Выход
     */
    public static function logout()
    {
        $time   =   time() - 100;
        setcookie('uid', 0, $time);
        setcookie('gid', 0, $time);
        setcookie('hash', '', $time);
        unset($_COOKIE['uid'], $_COOKIE['gid'], $_COOKIE['hash']);
    }
}