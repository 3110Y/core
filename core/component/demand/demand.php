<?php

namespace core\component\demand;

use core\component\{
    registry\registry
};

/**
 * Class demand
 * @package application\admin\model
 */
class demand
{
    /**
     * @var string $table
     */
    protected $table;

    /**
     * Запрос листинга
     *
     * @param array $where
     * @param string $order
     * @param string $limit
     *
     * @return array
     */
    public function getList(array $where = [], string $order = '', string $limit = '') :array
    {
        $result = [];

        /** @var \core\component\PDO\PDO $db */
        $db = registry::get('db');
        $rows = $db->selectRows($this->table, '*', $where, $order, $limit);

        if (!empty($rows)) {
            $result = $rows;
        }

        return $result;
    }

    /**
     * Количество всех записей
     *
     * @param array $where
     *
     * @return int
     */
    public function getListCount(array $where = []) :int
    {
        /** @var \core\component\PDO\PDO $db */
        $db = registry::get('db');
        return $db->selectCount($this->table, '*', $where);
    }

    /**
     * Выборка одного элемента
     *
     * @param array $where
     * @return array
     */
    public function getItem(array $where = []) :array
    {
        $result = [];

        if (!empty($where)) {
            /** @var \core\component\PDO\PDO $db */
            $db = registry::get('db');
            $row = $db->selectRow($this->table, '*', $where);

            if (!empty($row)) {
                $result = $row;
            }
        }

        return $result;
    }

    /**
     * Выборка элемента по ID
     *
     * @param int $id
     * @return array
     */
    public function getItemByID(int $id = 0) :array
    {
        $result = [];

        if (!empty($id)) {
            $result = $this->getItem(['id' => $id]);
        }

        return $result;
    }

    /**
     * Запись нового элемента
     *
     * @param array $data
     * @return int
     */
    public function setItem(array $data = []) :int
    {
        $result = 0;

        if (!empty($data)) {
            /** @var \core\component\PDO\PDO $db */
            $db = registry::get('db');
            $db->inset($this->table, $data);
            $result = $db->getLastID();
        }

        return $result;
    }

    /**
     * Обновление элементов
     *
     * @param array $data
     * @param array $where
     *
     * @return bool
     */
    public function updateItem(array $data = [], array $where = []) :bool
    {
        $result = false;

        if (!empty($data)) {
            /** @var \core\component\PDO\PDO $db */
            $db = registry::get('db');
            $result = $db->update($this->table, $data, $where);
            $result = $result->execute();
        }

        return $result;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }


}