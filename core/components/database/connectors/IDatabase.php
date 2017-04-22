<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:51
 */

namespace core\components\database\connectors;

/**
 * Interface IDatabase
 * Коннектор Базы данных
 * @package core\components\database\connectors
 */
interface IDatabase
{

    /**
     * Создает
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @return resource
     */
    public function create($table = null, $fields = null);

    /**
     * Вставляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @return resource
     */
    public function inset($table = null, $value = null);

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
    public function select($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null);

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
    public function selectRow($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null);

    /**
     * Выбирает записи
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @param mixed $having указание условий в результах агрегатных функций
     * @return array
     */
    public function selectRows($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null);

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
    public function selectCount($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null);

    /**
     * Обновляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @param mixed $where условия
     * @return resource
     */
    public function update($table = null, $value = null, $where = null);

    /**
     * Удаляет запись
     * @param mixed $table таблица
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @return resource
     */
    public function dell($table = null, $where = null, $order = null, $limit = null);

    /**
     * колонки
     * @param mixed $table таблица
     * @return resource
     */
    public function column($table = null);

    //TODO: show tables

    /**
     * Зачищяет
     * @param mixed $table таблица
     * @return resource
     */
    public function truncate($table = null);

    /**
     * Удаляет таблицу
     * @param mixed $table таблица
     * @return resource
     */
    public function drop($table = null);

    /**
     * заключает строку в кавычки (если требуется) и экранирует специальные символы внутри строки подходящим для драйвера способом.
     * @param string $string Экранируемая строка.
     * @param mixed $param Представляет подсказку о типе данных первого параметра для драйверов, которые имеют альтернативные способы экранирования.
     * @return string
     */
    public function quote($string, $param = false);

    /**
     * Подготавливает SQL запрос к базе данных к запуску
     * @param string $sql SQL - запрос
     * @return resource
     */
    public function prepare($sql);

    /**
     * Выполняет запрос
     * @param string $sql SQL - запрос
     * @return resource
     */
    public function query($sql);

    /**
     * Возвращает ID последней вставленной строки или последовательное значение
     * @return int
     */
    public function getLastID();

    /**
     * переустанавливает соединение, если то случайно умерло
     * @return void;
     */
    public function ping();

    /**
     * отдает коннект
     * @return resource;
     */
    public function getConnect();


}