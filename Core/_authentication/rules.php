<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 28.12.2017
 * Time: 0:03
 */

namespace Core\_authentication;


class rules extends AAuthentication
{
    /**
     * проверет правило
     * @param string $key
     * @return bool
     */
    public function check(string $key)
    {
        $where = Array(
            'key' => $key,
        );
        $rowObject = $this->db->selectRow($this->config['object'], 'id', $where);
        $where = Array(
            'user_id' => user::get(),
            Array(
              'field'       =>  'user_id',
              'condition'   =>  '!=',
              'value'       =>  '0'
            ),
            'object_id' => $rowObject['id'],
            'status'    =>  1
        );
        $row = $this->db->selectRow($this->config['rules'], '*', $where, '`priority` ASC', ' 0, 1 ');
        if ($row !== false) {
            return $row['action'] ? true : false;
        }
        $group = implode(',', group::get());
        $where = Array(
            "FIND_IN_SET(`group_id`, '{$group}')",
            Array(
                'field'       =>  'group_id',
                'condition'   =>  '!=',
                'value'       =>  '0'
            ),
            'object_id' => $rowObject['id'],
            'status'    =>  1
        );
        $row = $this->db->selectRow($this->config['rules'], '*', $where, '`priority` ASC', ' 0, 1 ');
        if ($row !== false) {
            return $row['action'] ? true : false;
        }
        $where = Array(
            'user_id'   => 0,
            'group_id' => 0,
            'object_id' => $rowObject['id'],
            'status'    =>  1
        );
        $row = $this->db->selectRow($this->config['rules'], '*', $where, '`priority` ASC', ' 0, 1 ');
        if ($row !== false) {
            return $row['action'] ? true : false;
        }
        return true;
    }
}