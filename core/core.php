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
     * @var string Путь до пространства
     */
    private $path = '';
    /**
     * @const string Путь до компонентов
     */
    const components = '\core\components\\';

    /**
     * Инициализация
     * @param array $architecture архитектура приложения
     */
    public static function init(array $architecture = Array())
    {
        self::prepareArchitecture($architecture);
    }

    /**
     * Отдает компонент
     * @param string $name имя компонента
     * @param string $file файл
     * @return bool
     */
    public static function getComponents($name, $file = '\controller')
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

    /**
     * Устанавливет архитектуру приложения
     * @param array $architecture архитектура приложения
     * @param array $path путь
     */
    private static function prepareArchitecture(array $architecture = Array(),array $path = Array())
    {
        foreach ($architecture as $key => $value) {
            $newPath = $path;
            if (!is_int($key)) {
                $newPath[]     = $key;
            }
            if(is_string($value)) {
                $namespace  = implode('\\\\',$newPath) . '\\\\';
                $core = new self();
                $core->register($namespace . $value);
            } elseif (is_array($value)) {
                self::prepareArchitecture($value, $newPath);
            }
        }
    }

    /**
     * Устанавливает автозагрузку
     * @param string $regular регулярное выражение
     * @return bool
     */
    public function register($regular)
    {
        $this->regular      = $regular;
        $this->path         = $_SERVER['DOCUMENT_ROOT'] . '/';
        $this->key          = md5($this->path . $this->regular);
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
     * @see $regularAutoload
     * @see $pathAutoload
     * @param string $className загружаемый класс
     * @return bool
     */
    private function loader($className)
    {
        $classSearch    =   ltrim($className, '\\');
        preg_match('/^' . $this->regular . '$/i', $classSearch, $output);
        if (isset($output[0])) {
            $file = str_replace('\\', '/', $this->path . $output[0] . '.php');
            if (file_exists($file)) {
                include_once $file;
            } return true;
        }
        return false;
    }

}
