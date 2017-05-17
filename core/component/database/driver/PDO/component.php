<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:42
 */

namespace core\component\database\driver\PDO;
use core\component\database as database;


/**
 * Class component
 * @package core\component\database\driver\PDO
 */
class component extends database\ADriver implements database\IDriver
{
    /**
     * @const float Версия ядра
     */
    const VERSION   =   1.0;
    /**
     * @var null|\PDO соединение
     */
    private   $connect   =   null;
    /**
     * @var array настройки по умолчанию
     */
    private   $config    =   Array(
        'driver'            =>  'mysql',
        'host'              =>  '127.0.0.1',
        'port'              =>  '3306',
        'db'                =>  '',
        'name'              =>  '',
        'pass'              =>  '',
        'character'         =>  'UTF8',
    );


    /**
     * component constructor.
     * @param array $config конфиг
     */
    protected function __construct($config = Array())
    {
        if(!extension_loaded('pdo')) {
            //TODO: обработка ошибок
            die('Нет Соединения  с PDO');
        }
        try {
            $this->config['driver']     =   isset($config['driver'])        ?   $config['driver']       :   $this->config['driver'];
            $this->config['host']       =   isset($config['host'])          ?   $config['host']         :   $this->config['host'];
            $this->config['port']       =   isset($config['port'])          ?   $config['port']         :   $this->config['port'];
            $this->config['db']         =   isset($config['db'])            ?   $config['db']           :   $this->config['db'];
            $this->config['name']       =   isset($config['name'])          ?   $config['name']         :   $this->config['name'];
            $this->config['pass']       =   isset($config['pass'])          ?   $config['pass']         :   $this->config['pass'];
            $this->config['character']  =   isset($config['character'])     ?   $config['character']    :   $this->config['character'];
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
                ';dbname=' . $this->config['db'];
            $this->connect = new \PDO($dns, $this->config['name'], $this->config['pass'], $params);
            $this->connect->exec('SET NAMES '.$this->config['character'] );
            $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT);
            $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->connect->setAttribute(\PDO::ATTR_EMULATE_PREPARES,false);
            $this->connect->query("SET character_setconnection = {$this->config['character']};" );
            $this->connect->query("SET character_set_client = {$this->config['character']};" );
            $this->connect->query("SET character_set_results = {$this->config['character']};" );
            $this->connect->query('SET NAMES ' . $this->config['character']);;
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
     * @return \PDOStatement
     */
    public function create($table = null, $fields = null)
    {
        $sql = $this->createGenerator($table, $fields);
        return $this->query($sql);
    }

    /**
     * Вставляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @return \PDOStatement
     */
    public function inset($table = null, $value = null)
    {
        $result = $this->insetGenerator($table, $value);
        $query  =   $this->getConnect()->prepare($result['sql']);
        $query->execute($result['execute']);
        return $query;
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
     * @return \PDOStatement
     */
    public function select($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {
        $result = self::selectGenerator($table, $fields, $where, $order, $limit, $group, $having);
        $query  =   $this->getConnect()->prepare($result['sql']);
        $query->execute($result['execute']);
        return $query;
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
     * @return array
     */
    public function selectRow($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {
        $query  =   $this->select($table, $fields, $where, $order, $limit, $group, $having);
        return $query->fetch();
    }


    public function selectRows($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {
        $query  =   $this->select($table, $fields, $where, $order, $limit, $group, $having);
        return $query->fetchAll();
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
     * @return int
     */
    public function selectCount($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {
        $query  =   $this->select($table, $fields, $where, $order, $limit, $group, $having);
        return $query->rowCount();
    }

    /**
     * Обновляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @param mixed $where условия
     * @return \PDOStatement
     */
    public function update($table = null, $value = null, $where = null)
    {
        $result = $this->updateGenerator($table, $value , $where);
        $query  =   $this->getConnect()->prepare($result['sql']);
        $query->execute($result['execute']);
        return $query;
    }

    /**
     * Удаляет запись
     * @param mixed $table таблица
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @return \PDOStatement
     */
    public function dell($table = null, $where = null, $order = null, $limit = null)
    {
        $result = $this->dellGenerator($table, $where, $order, $limit);
        $query  =   $this->getConnect()->prepare($result['sql']);
        $query->execute($result['execute']);
        return $query;
    }

    /**
     * колонки
     * @param mixed $table таблица
     * @return \PDOStatement
     */
    public function column($table = null)
    {
        $result = $this->columnGenerator($table);
        $query  =   $this->getConnect()->prepare($result['sql']);
        $query->execute($result['execute']);
        return $query;
    }

    //TODO: show tables

    /**
     * Зачищяет
     * @param mixed $table таблица
     * @return \PDOStatement
     */
    public function truncate($table = null)
    {
        $sql = $this->truncateGenerator($table);
        return $this->query($sql);
    }

    /**
     * Удаляет таблицу
     * @param mixed $table таблица
     * @return \PDOStatement
     */
    public function drop($table = null)
    {
        $sql = $this->dropGenerator($table);
        return $this->query($sql);
    }

    /**
     * заключает строку в кавычки (если требуется) и экранирует специальные символы внутри строки подходящим для драйвера способом.
     * @param string $string Экранируемая строка.
     * @param mixed $param Представляет подсказку о типе данных первого параметра для драйверов, которые имеют альтернативные способы экранирования.
     * @return string
     */
    public function quote($string, $param = false)
    {
        if($param !== false) {
            return $this->connect->quote($string, $param);
        }
        return $this->connect->quote($string);
    }

    /**
     * Подготавливает запрос к выполнению и возвращает ассоциированный с этим запросом объект
     * @param string $sql SQL - запрос
     * @return \PDOStatement
     */
    public function prepare($sql)
    {
        return $this->connect->prepare($sql);
    }

    /**
     * Выполняет запрос
     * @param string $sql SQL - запрос
     * @return \PDOStatement
     */
    public function query($sql)
    {
        return $this->connect->query($sql);
    }

    /**
     * Возвращает ID последней вставленной строки или последовательное значение
     * @return int
     */
    public function getLastID()
    {
        return $this->connect->lastInsertId();
    }

    /**
     * переустанавливает соединение, если то случайно умерло
     * @return void;
     */
    public function ping()
    {
        $this->connect->query("SELECT 1");
    }

    /**
     * отдает коннект
     * @return null|\PDO
     */
    public function getConnect()
    {
        return  $this->connect;
    }
}