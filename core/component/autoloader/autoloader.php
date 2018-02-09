<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 09.02.18
 * Time: 14:36
 */
namespace core\component\autoloader;


use core\component\config\config;

/**
 * Class autoloader
 *
 * @package core\component\autoloader
 */
class autoloader
{
    /**
     * @var array Ассоциативный массив. Ключи содержат префикс пространства имён,
     * значение — массив базовых директорий для классов в этом пространстве имён.
     */
    private $prefixes = array();

    /**
     * экземпляр
     * @var autoloader
     */
    private static $instance;

    /**
     * @return autoloader
     */
    public static function getInstance(): autoloader
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return  self::$instance;
    }


    /**
    private function __construct(){}



    /**
     * Регистрирует загрузчик в стеке загрузчиков SPL.
     *
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Добавляет базовую директорию к префиксу пространства имён.
     *
     * @param string $prefix Префикс пространства имён.
     * @param string $baseDir Базовая директория для файлов классов из пространства имён.
     * @param bool $prepend Если true, добавить базовую директорию в начало стека.
     * В этом случае она будет проверяться первой.
     * @return void
     */
    public function addNamespace($prefix, $baseDir = '', $prepend = false): void
    {
        if($baseDir === '') {
            $baseDir = $prefix;
        }
        // нормализуем префикс пространства имён
        $prefix = trim($prefix, '\\') . '\\';

        // нормализуем базовую директорию так, чтобы всегда присутствовал разделитель в конце
        $base_dir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        // инициализируем массив префиксов пространства имён
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }

        // сохраняем базовую директорию для префикса пространства имён
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            $this->prefixes[$prefix][] = $base_dir;
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
            $mappedFile = $this->loadMappedFile($prefix, $relative_class);
            if ($mappedFile) {
                return $mappedFile;
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
     * @param string $relativeClass Относительное имя класса.
     * @return mixed false если файл не был загружен. Иначе имя загруженного файла.
     */
    private function loadMappedFile($prefix, $relativeClass)
    {
        // есть ли у этого префикса пространства имён какие-либо базовые директории?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // ищем префикс в базовых директориях
        if (!empty($this->prefixes[$prefix])) {
            foreach ($this->prefixes[$prefix] as $baseDir) {

                // заменяем префикс базовой директорией,
                // заменяем разделители пространства имён на разделители директорий,
                // к относительному имени класса добавляем .php
                $file = $baseDir
                    . str_replace('\\', '/', $relativeClass)
                    . '.php';
                // если файл существует, загружаем его
                if ($this->requireFile($file)) {
                    // ура, получилось
                    return $file;
                }
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
    private function requireFile($file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        if (file_exists(config::getDR() . $file)) {
            require config::getDR() . $file;
            return true;
        }
        return false;
    }

}