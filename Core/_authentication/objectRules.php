<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:02
 */

namespace Core\_authentication;


class objectRules extends component
{
    /**
     * @param string $key
     * @param string $name
     */
    public function register(string $key, string $name)
    {
        $where = Array(
            'key' => $key,
        );
        $row = $this->db->selectRow($this->config['object'], '*', $where);
        if ($row === false) {
            $value = Array(
                'key'   => $key,
                'name'  => $name,
            );
            $this->db->inset($this->config['object'], $value);
        } elseif ($name != $row['name']) {
            $value = Array(
                'name'  => $name,
            );
            $this->db->update($this->config['object'], $value, $where);
        }
    }
}