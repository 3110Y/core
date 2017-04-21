<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:42
 */

namespace core\components\PDO;
use core\components\database\connectors as databaseConnectors;
/**
 * Class component
 * компонент PDO
 * @package core\components\PDO
 */
class component extends databaseConnectors\ADatabase implements databaseConnectors\IDatabase
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @const
     */
    const NAME  =   'PDO';

    private   $connect   =   null;
    private   $config    =   Array(
        'driver'            =>  'mysql',
        'host'              =>  '127.0.0.1',
        'port'              =>  '3306',
        'schema'            =>  '',
        'name'              =>  '',
        'pass'              =>  '',
        'character'         =>  'UTF8',
    );
    /**
     * @var mixed|null|object экземпляр
     */
    private static $instance = null;

    /**
     * Одиночка
     * @param array $config конфиг
     * @return component|mixed|null|object
     */
    public static function getInstance($config = array()) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * component constructor.
     * @param array $config конфиг
     */
    private function __construct($config = Array())
    {
        if(!extension_loaded('pdo')) {
            //TODO: обработка ошибок
            die('Нет Соединения  с PDO');
        }
        try {
            $params = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            );
            if ($this->config['driver'] == 'mysql')
            {
                $params[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES '{$this->config['character']}'";
            }
            $params[\PDO::ATTR_PERSISTENT] = true;
            $dns = $this->config['driver'] . ':host=' . $this->config['host'] .
                ((!empty($this->config['port'])) ? (';port=' . $this->config['port']) : '') .
                ';dbname=' . $this->config['schema'];
            $this->connect = new \PDO($dns, $this->config['name'], $this->config['pass'], $params);
            $this->connect->exec('SET NAMES '.$this->config['character'] );
            $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
            $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->connect->setAttribute(\PDO::ATTR_EMULATE_PREPARES,false);
            $this->connect->query('SET character_setconnection = '.$this->config['character'].';' );
            $this->connect->query('SET character_set_client = ' . $this->config['character'] . ';' );
            $this->connect->query('SET character_set_results = ' . $this->config['character']. ';' );
            $this->connect->query('SET NAMES '.$this->config['character']);;
        } catch (\PDOException $e) {
            //TODO: обработка ошибок
            die("Mysql error ".$e->getMessage());
        }
        return $this;
    }

    /**
     * Создает
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @return resource
     */
    public function create($table = null, $fields = null)
    {

    }

    /**
     * Вставляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @return resource
     */
    public function inset($table = null, $value = null)
    {

    }

    /**
     * Выбирает
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @param mixed $having указание условий в результах агрегатных функций
     * @return resource
     */
    public function select($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {

    }

    /**
     * Выбирает 1 запись
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @param mixed $having указание условий в результах агрегатных функций
     * @return resource
     */
    public function selectRow($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {

    }

    /**
     * Выбирает записи
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @param mixed $having указание условий в результах агрегатных функций
     * @return resource
     */
    public function selectRows($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {

    }

    /**
     * Выбирает количество
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @param mixed $having указание условий в результах агрегатных функций
     * @return resource
     */
    public function selectCount($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {

    }

    /**
     * Обновляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @param mixed $where условия
     * @return resource
     */
    public function update($table = null, $value = null, $where = null)
    {

    }

    /**
     * Удаляет запись
     * @param mixed $table таблица
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @return resource
     */
    public function dell($table = null, $where = null, $order = null, $limit = null)
    {

    }

    /**
     * колонки
     * @param mixed $table таблица
     * @return resource
     */
    public function column($table = null)
    {

    }

    //TODO: show tables

    /**
     * Зачищяет
     * @param mixed $table таблица
     * @return resource
     */
    public function truncate($table = null)
    {

    }

    /**
     * Удаляет таблицу
     * @param mixed $table таблица
     * @return resource
     */
    public function drop($table = null)
    {

    }

    /**
     * заключает строку в кавычки (если требуется) и экранирует специальные символы внутри строки подходящим для драйвера способом.
     * @param string $string Экранируемая строка.
     * @return string
     */
    public function quote($string)
    {

    }

    /**
     * Подготавливает SQL запрос к базе данных к запуску
     * @param string $sql SQL - запрос
     * @return resource
     */
    public function prepare($sql)
    {

    }

    /**
     * Выполняет запрос
     * @param string $sql SQL - запрос
     * @return bool
     */
    public function query($sql)
    {

    }

    /**
     * Возвращает ID последней вставленной строки или последовательное значение
     * @return int
     */
    public function getLastID()
    {

    }

    /**
     * переустанавливает соединение, если то случайно умерло
     * @return void;
     */
    public function ping()
    {

    }

    /**
     * отдает коннект
     * @return resource;
     */
    public function getConnect()
    {

    }
}