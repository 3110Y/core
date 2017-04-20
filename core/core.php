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
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'core';
    /**
     * @const string Путь до компонентов
     */
    const components = '\core\components\\';
    /**
     * Ассоциативный массив. Ключи содержат префикс пространства имён,
     * значение — массив базовых директорий для классов в этом пространстве имён.
     *
     * @var array
     */
    protected $prefixes = array();
    /**
     * экземпляр
     * @var null
     */
    private static $instance = null;


    /**
     * @return core|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return  self::$instance;
    }


    /**
     * core constructor.
     */
    final public function __construct() {}

    /**
     * Регистрирует загрузчик в стеке загрузчиков SPL.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Добавляет базовую директорию к префиксу пространства имён.
     *
     * @param string $prefix Префикс пространства имён.
     * @param string $base_dir Базовая директория для файлов классов из пространства имён.
     * @param bool $prepend Если true, добавить базовую директорию в начало стека.
     * В этом случае она будет проверяться первой.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // нормализуем префикс пространства имён
        $prefix = trim($prefix, '\\') . '\\';

        // нормализуем базовую директорию так, чтобы всегда присутствовал разделитель в конце
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

        // инициализируем массив префиксов пространства имён
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // сохраняем базовую директорию для префикса пространства имён
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * Загружает файл для заданного имени класса.
     *
     * @param string $class Абсолютное имя класса.
     * @return mixed Если получилось, полное имя файла. Иначе — false.
     */
    public function loadClass($class)
    {
        // текущий префикс пространства имён
        $prefix = $class;

        // для определения имени файла обходим пространства имён из абсолютного
        // имени класса в обратном порядке
        while (false !== $pos = strrpos($prefix, '\\')) {

            // сохраняем завершающий разделитель пространства имён в префиксе
            $prefix = substr($class, 0, $pos + 1);

            // всё оставшееся — относительное имя класса
            $relative_class = substr($class, $pos + 1);

            // пробуем загрузить соответсвующий префиксу и относительному имени класса файл
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // убираем завершающий разделитель пространства имён для следующей итерации strrpos()
            $prefix = rtrim($prefix, '\\');
        }

        // файл так и не был найден
        return false;
    }

    /**
     * Загружает файл, соответствующий префиксу пространства имён и относительному имени класса.
     *
     * @param string $prefix Префикс пространства имён.
     * @param string $relative_class Относительное имя класса.
     * @return mixed false если файл не был загружен. Иначе имя загруженного файла.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        // есть ли у этого префикса пространства имён какие-либо базовые директории?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // ищем префикс в базовых директориях
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // заменяем префикс базовой директорией,
            // заменяем разделители пространства имён на разделители директорий,
            // к относительному имени класса добавляем .php
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';
            // если файл существует, загружаем его
            if ($this->requireFile($file)) {
                // ура, получилось
                return $file;
            }
        }

        // файл так и не был найден
        return false;
    }

    /**
     * Если файл существует, загружеаем его.
     *
     * @param string $file файл для загрузки.
     * @return bool true, если файл существует, false — если нет.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        } elseif ($_SERVER['DOCUMENT_ROOT'] . $file) {
            require $file;
            return true;
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
