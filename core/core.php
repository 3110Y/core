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
final class core
{
    /**
     * @const float Версия ядра
     * 1 версия суммы
     * .
     * 2 версия файла ядра или суммы предыдущего
     * 3 версия компонентов или предыдущего
     * 4 версия исправления ошибок или предыдущего
     * 5 версия хотфиксов
     */
    const VERSION   =   1.8890;
    
    /**
     * @const string Путь до компонентов
     */
    const components = '\core\components\\';
    /**
     * @var array Ассоциативный массив. Ключи содержат префикс пространства имён,
     * значение — массив базовых директорий для классов в этом пространстве имён.
     */
    protected $prefixes = array();
    /**
     * экземпляр
     * @var null
     */
    private static $instance = null;
    /**
     * @var string CORE ROOT
     */
    private static $DR = '';
	/**
	 * @var array хранит конфигурации
	 */
    private static $config = Array();
	/**
	 * @var string директория конфигурации
	 */
    private static $dirConfig = '';
    /**
	 * @var string директория для файлов и кеша
	 */
    private static $fileCache = '';

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
    private function __construct(){}

    /**
     * Устанавливает CORE ROOT;
     * @param string $DR DOCUMENT ROOT
     */
    public static function setDR(string $DR = __DIR__)
    {
        self::$DR  =   str_replace('\\', '/', $DR);
    }

	/**
	 * Отдает CORE ROOT
	 *
	 * @param bool $notSlash
	 *
	 * @return string CORE ROOT;
	 */
    public static function getDR($notSlash = false)
    {
        if (self::$DR !== '') {
        	if ($notSlash) {
		        return self::$DR;
	        } else {
		        return self::$DR . '/';
	        }
        }
        if (isset($_SERVER['DOCUMENT_ROOT'])) {
            return $_SERVER['DOCUMENT_ROOT'];
        } else {
            return str_replace('\\', '/', str_replace('\core', '', __DIR__));
        }
    }

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
     * @param string $baseDir Базовая директория для файлов классов из пространства имён.
     * @param bool $prepend Если true, добавить базовую директорию в начало стека.
     * В этом случае она будет проверяться первой.
     * @return void
     */
    public function addNamespace($prefix, $baseDir = '', $prepend = false)
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
    protected function loadMappedFile($prefix, $relativeClass)
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
    protected function requireFile($file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        if (file_exists(self::getDR() . $file)) {
            require self::getDR() . $file;
            return true;
        }
        return false;
    }

	/**
	 * Устанавливает директорию для файлов и кеша
	 * @param string $fileCache директория для файлов и кеша
	 */
    public static function setDirFileCache(string $fileCache)
    {
    	self::$fileCache = self::getDR() . $fileCache . DIRECTORY_SEPARATOR;
    }

    /**
	 * Отдает директорию для файлов и кеша
     *
     * @return string директория для файлов и кеша
	 */
    public static function getDirFileCache(): string
    {
    	return self::$fileCache;
    }

    /**
	 * Устанавливает директорию конфигурации
	 * @param string $dirConfig директория конфигурации
	 */
    public static function setDirConfig(string $dirConfig)
    {
    	self::$dirConfig = self::getDR() . $dirConfig . DIRECTORY_SEPARATOR;
    }

	/**
	 * Отдает определенный конфиг
	 * @param string $configName имя конфига
	 *
	 * @return array|mixed конфиг
	 */
    public static function getConfig(string $configName)
    {
    	if (isset(self::$config[$configName])) {
    		return self::$config[$configName];
	    }
	    $globalDirConfig = self::$dirConfig . $configName . '.php';
    	if (file_exists($globalDirConfig)) {
    		$config = include $globalDirConfig;
			self::$config[$configName] = $config;
		    return $config;
	    }
	    return Array();
    }
}
