<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 17:44
 */

namespace app\client\classes;

/**
 * Class session
 * Работа с сессиями
 * singleton
 * @package app\classes
 */
class session
{
    /**
     * @var mixed|null|session   экземпляр
     */
    private static $instance;

    /**
     * singleton
     * @return session|mixed|null экземпляр
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * session constructor.
     */
    final private function __construct()
    {
        session_start();
    }

    final public function   __call($name, $arguments){}
    final private function __clone(){}

    /**
     * Устанавливает значение
     * @param string $key ключ
     * @param string $value значение
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Отдает значение
     * @param string $key ключ
     * @return mixed|bool|string значение
     */
    public function get($key)
    {
        if ($this->exist($key)) {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * Проверяет наличие
     * @param string $key ключ
     * @return bool
     */
    public function exist($key)
    {
        if (isset($_SESSION[$key])) {
            return true;
        }
        return false;
    }
}

