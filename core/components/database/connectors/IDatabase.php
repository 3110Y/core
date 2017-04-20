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
     * Слздает
     * @param mixed $table
     * @param mixed $fields
     * @return resource
     */
    public function create($table = null, $fields = null);

    /**
     * Вставляет
     * @param mixed $table
     * @param mixed $fields
     * @return resource
     */
    public function inset($table = null, $fields = null);

    /**
     * Выбирает
     * @param mixed $table
     * @param mixed $fields
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $group
     * @return resource
     */
    public function select($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null);

    /**
     * Обновляет
     * @param mixed $table
     * @param mixed $fields
     * @return resource
     */
    public function update($table = null, $fields = null);

    /**
     * Удаляет запись
     * @param mixed $table
     * @param mixed $where
     * @return resource
     */
    public function dell($table = null, $where = null);

    /**
     * Зачищяет
     * @param mixed $table
     * @return resource
     */
    public function truncate($table = null);

    /**
     * Удаляет таблицу
     * @param mixed $table
     * @return resource
     */
    public function drop($table = null);

}