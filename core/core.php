<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 12:22
 */

namespace core;


/**
 * Class core
 * @package core
 */
class core
{
    /**
     * @var string ключ Автозагрузчика
     */
    private $key = '';
    /**
     * @var string регулярное выражение
     */
    private $regular = '';
    /**
     * @var string пространство
     */
    private $namespace = '';
    /**
     * @var string префикс пространства
     */
    private $prefix = '';
    /**
     * @const string Путь до компонентов
     */
    const components = '\core\components\\';

    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;

    /**
     * @const
     */
    const NAME  =   'core';

    /**
     * Инициализация
     * @param string $prefix расположение
     */
    public static function init($prefix = '')
    {
        $core = new self();
        $core->register('core', $prefix);
    }

    /**
     * Устанавливает автозагрузку
     * @see registerAutoload()
     * @param string $namespace директория пространства
     * @param string $prefix префикс директории пространства
     * @return bool результат
     */
    public static function register($namespace, $prefix = '')
    {
        return (new self())->registerAutoload($namespace, $prefix);
    }

    /**
     * Устанавливает автозагрузку
     * @param string $namespace директория пространства
     * @param string $prefix префикс директории пространства
     * @return bool
     */
    public function registerAutoload($namespace, $prefix = '')
    {
        $this->namespace    = $namespace;
        $this->prefix         = $prefix;
        $this->key          = md5($this->prefix . $this->namespace);
        $this->regular      = '/^' . $this->namespace . '\\\\[a-z\\\\]+$/i';
        $autoload = spl_autoload_functions();
        if (spl_autoload_functions() !== false) {
            foreach ($autoload as $function) {
                if (!is_string($function) && isset($function[0]->key) && $this->key === $function[0]->key) {
                    return false;
                }
            }
        }
        return spl_autoload_register(array($this, 'loader'));
    }

    /**
     * Загружает класс
     * @param string $className загружаемый класс
     * @return bool
     */
    private function loader($className)
    {

        $classSearch    =   ltrim($className, '\\');
        preg_match($this->regular, $classSearch, $output);
        if (isset($output[0])) {
            $file = $_SERVER['DOCUMENT_ROOT'] . str_replace('\\', '/', $this->prefix . $output[0] . '.php');
            if (file_exists($file)) {
                include_once $file;
            } return true;
        }
        return false;
    }



    /**
     * Отдает компонент
     * @param string $name имя компонента
     * @param string $file файл
     * @return bool
     */
    public static function getComponents($name, $file = '\component')
    {
        $components   =   self::components . $name . $file;
        $data = array(
            '_' => DIRECTORY_SEPARATOR,
            '\\' => DIRECTORY_SEPARATOR,
        );
        $file   =   $_SERVER['DOCUMENT_ROOT'] . strtr($components, $data) . '.php';
        if (file_exists($file)) {
            return new $components();
        }
        return false;
    }

}
