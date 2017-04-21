<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:51
 */

namespace core\components\database\connectors;

/**
 * Class ADatabase
 * Коннектор Базы данных
 * @package core\components\database\connectors
 */
class ADatabase
{

    /**
     * подготавливает поля
     * @param mixed $field
     * @return string
     */
    protected static function field($field)
    {
        if ($field === null) {
            $field = '*';
        } elseif (is_array($field)) {
            $array  =   array();
            foreach ($field as $key => $value) {
                if (is_int($key) && !is_array($value)) {
                    $array[]    =   $value;
                } elseif (is_string($key) && !is_array($value)) {
                    $array[]    =   "`{$key}` . `{$value}`";
                } elseif (is_array($value)) {
                    $f  =   array(
                        't' => null,
                        'v' => null,
                        'a' => null
                    );
                    $value  =   array_change_key_case($value, CASE_LOWER );
                    if (isset($value['t'])) {
                        $f['t'] =   $value['t'];
                    } elseif (isset($value['table'])) {
                        $f['t'] =   $value['table'];
                    } elseif (is_string($key)) {
                        $f['t'] =   $key;
                    }
                    if (isset($value['v'])) {
                        $f['v'] =   $value['v'];
                    } elseif (isset($value['value'])) {
                        $f['v'] =   $value['value'];
                    }  elseif (isset($value['val'])) {
                        $f['v'] =   $value['val'];
                    } elseif (isset($value['f'])) {
                        $f['v'] =   $value['f'];
                    } elseif (isset($value['field'])) {
                        $f['v'] =   $value['field'];
                    }
                    if (isset($value['a'])) {
                        $f['a'] =   $value['a'];
                    } elseif (isset($value['as'])) {
                        $f['a'] =   $value['as'];
                    } elseif (isset($value['associate'])) {
                        $f['a'] =   $value['associate'];
                    } elseif (isset($value['alias'])) {
                        $f['a'] =   $value['alias'];
                    }
                    $string   =     ($f['t'] != null)   ?   "`{$f['t']}` . "   :   '';
                    $string   .=    ($f['v'] != null)   ?   "`{$f['v']}`"   :   '';
                    $string   .=    ($f['a'] != null)   ?   " AS `{$f['v']}` "   :   '';
                    $array[]  =     $string;
                }
            }
            $field = implode(',', $array);
        }
        return $field;
    }

    /**
     * подготавливает таблицы
     * @param mixed $table
     * @use Array( 'users', Array('table_role', 'role'), Array('table_role', 'role', 'LEFT JOIN', 'ON'=>'id = user_id')
     * @return string
     */
    protected static function table($table)
    {
        if (is_array($table)) {
            $table  =   array_change_key_case($table, CASE_LOWER );
            $array  =   Array();
            foreach ($table as $key =>  $value) {
                if (is_int($key) && $value === ',') {
                    $array[]    =   ",";
                } elseif (is_int($key)    &&  is_string($value)) {
                    $array[]    =   "`{$value}`";
                } elseif (is_string($key)    &&  is_string($value)) {
                    $array[]    =   "`$key` `{$value}`";
                } elseif (is_array($value)) {
                    $value  =   array_change_key_case($value, CASE_LOWER );
                    $t = Array(
                        't' =>  null,
                        'a' =>  null,
                        'j' =>  null,
                        'o' =>  null
                    );
                    if (isset($value['j'])) {
                        $t['j'] =   $value['j'];
                    } elseif (isset($value['join'])) {
                        $t['j'] =   $value['join'];
                    }
                    $o  =  null;
                    if (isset($value['o'])) {
                        $o =   $value['o'];
                    } elseif (isset($value['on'])) {
                        $o =   $value['on'];
                    }
                    if (is_array($o)) {
                        $o  =   self::where($o);
                        $t['o'] =  $o['where'];
                    } elseif (is_string($o)) {
                        $t['o'] =   $o;
                    }
                    if (isset($value['t'])) {
                        $t['t'] =   $value['t'];
                    } elseif (isset($value['table'])) {
                        $t['t'] =   $value['table'];
                    }  elseif (is_string($key) ) {
                        $t['t'] =   $key;
                    } elseif (isset($value[0])) {
                        $t['t'] =   $value[0];
                    }
                    if (isset($value['a'])) {
                        $t['a'] =   $value['a'];
                    } elseif (isset($value['associate'])) {
                        $t['a'] =   $value['associate'];
                    } elseif (isset($value['alias'])) {
                        $t['a'] =   $value['alias'];
                    } elseif (isset($value[1])) {
                        $t['t'] =   $value[1];
                    }
                    $string   =     ($t['t'] != null)   ?   "`{$t['t']}` "   :   '';
                    $string   .=    ($t['a'] != null)   ?   " `{$t['a']}`"   :   '';
                    $string   .=    ($t['o'] != null)   ?   " {$t['o']} "    :   '';
                    $array[]  =     $string;
                }
            }
            $table = implode(',', $array);
        } else {
            $table = "`{$table}`";
        }
        return $table;
    }

    /**
     * подготавливает условия
     * @param mixed $where
     * @return array
     */
    protected static function where($where)
    {
        //TODO: переделать
        $execute   =   Array();
        $result = Array(
            'where'     =>  $where,
            'execute'   =>  $execute
        );
        if (is_array($where)) {
            $i=0;
            foreach ($where as $key => $value) {
                if($i%2 && (!is_int($key) || is_array($value))) {
                    $result['where'] .= ' AND ';
                    $i++;
                }
                if(is_int($key) && is_array($value)) {
                    $tmp_where = self::where($value);
                    $result['execute']   =       array_merge($result['execute'], $tmp_where['execute']);
                    $result['where']     .=      "({$tmp_where['sql']})";
                } elseif(is_int($key) && !is_array($value)) {
                    $where['where'] .= " {$value } ";
                } elseif(is_array($value)) {
                    if (!isset($value['condition']) && isset($value['c'])) {
                        $value['condition'] =   $value['c'];
                    }
                    if (!isset($value['value']) && isset($value['v'])) {
                        $value['value'] =   $value['v'];
                    }
                    if (!isset($value['prefix']) && isset($value['p'])) {
                        $value['prefix'] =   $value['p'];
                    }
                    if (isset($value['f'])) {
                            $key =   $value['f'];
                    }
                    if (isset($value['field'])) {
                            $key =   $value['field'];
                    }
                    $value['condition']             =   (isset($value['condition']))    ?   $value['condition']        :   '=';
                    $prefix                         =   (isset($value['prefix']))       ?   " `{$value['prefix']}`."  :   '';
                    if (!is_array($value['value'])) {
                        $result['where']                .=  $prefix . '`'.$key.'` '.(($value['condition'] == 'IN') ?   $value['condition'].' (:'.$key.')'  :   $value['condition'].' :'.$key);
                        $result['execute'][':'.$key]     =   $value['value'];
                    } elseif(is_array($value['value']) && $value['condition']  == 'IN' ) {
                        $keyArray = Array();
                        for ($i=0;$i<count($value['value']);$i++) {
                            $result['execute'][':'.$key.$i]     =   $value['value'][$i];
                            $keyArray[]   = ':'.$key.$i;
                        }
                        $result['where']                   .= $prefix.'`'.$key.'` '.$value['condition'].' ('.implode(",",$keyArray).')';
                    }
                } else {
                    $result['where']                   .=  '`'.$key.'` = :'.$key;
                    $result['execute'][':'.$key]     =   $value;
                }
                $i++;
            }
        }
        $result['where']  = ($result['where'] != null && $result['where'] != '')    ?   " WHERE {$result['where']} "  : '';
        return $result;
    }

    /**
     * подготавливает сортировку
     * @param mixed $order
     * @return string
     */
    protected static function order($order)
    {
        if ($order === null) {
            $order = null;
        } elseif (is_array($order)) {
            $array  =   array();
            foreach ($order as $key => $value) {
                if (is_int($key) && !is_array($value)) {
                    $array[]    =   $value;
                } elseif (is_string($key) && !is_array($value)) {
                    $array[]    =   "`{$key}` `{$value}`";
                } elseif (is_array($value)) {
                    $o  =   array(
                        'f' => null,
                        'd' => null,
                        'a' => null,
                    );
                    $value  =   array_change_key_case($value, CASE_LOWER );
                    if (isset($value['f'])) {
                        $o['f'] =   $value['f'];
                    } elseif (isset($value['fields'])) {
                        $o['f'] =   $value['fields'];
                    } elseif (is_string($key)) {
                        $o['f'] =   $key;
                    } elseif (isset($value[0])) {
                        $o['f'] =   $key;
                    }
                    if (isset($value['d'])) {
                        $o['d'] =   $value['d'];
                    } elseif (isset($value['direction'])) {
                        $o['d'] =   $value['direction'];
                    } elseif (isset($value[1])) {
                        $o['d'] =   $key;
                    }
                    if (isset($value['a'])) {
                        $o['a'] =   $value['a'];
                    } elseif (isset($value['as'])) {
                        $o['a'] =   $value['as'];
                    } elseif (isset($value['associate'])) {
                        $o['a'] =   $value['associate'];
                    } elseif (isset($value['alias'])) {
                        $o['a'] =   $value['alias'];
                    } elseif (isset($value['t'])) {
                        $o['a'] =   $value['t'];
                    } elseif (isset($value['table'])) {
                        $o['a'] =   $value['table'];
                    }
                    $string   =     ($o['a'] != null)   ?   "`{$o['a']}` . "        :   '';
                    $string   .=    ($o['f'] != null)   ?   "`{$o['f']}`"           :   '';
                    $string   .=    ($o['d'] != null)   ?   " `{$o['d']}` "         :   '';
                    $array[]  =     $string;
                }
            }
            $order = implode(',', $array);
        }
        return $order;
    }

    /**
     * подготавливает лимит
     * @param mixed $limit
     * @return string
     */
    protected static function limit($limit)
    {
        if ($limit === null) {
            $limit = null;
        } elseif (is_array($limit)) {
            $l = Array(
                'f' => 0,
                't' => 30,
            );
            $limit  =   array_change_key_case($limit, CASE_LOWER );
            if (isset($limit['f'])) {
                $l['f'] =   $limit['f'];
            } elseif (isset($limit['from'])) {
                $l['f'] =   $limit['from'];
            } elseif (isset($limit[0])) {
                $l['f'] =   $limit[0];
            }
            if (isset($limit['t'])) {
                $l['t'] =   $limit['t'];
            } elseif (isset($limit['to'])) {
                $l['f'] =   $limit['to'];
            } elseif (isset($limit[1])) {
                $l['f'] =   $limit[1];
            }
            $limit = implode(',', $l);
        }
        return $limit;
    }

    /**
     * подготавливает группировку
     * @param mixed $group
     * @return string
     */
    protected static function group($group)
    {
        if ($group === null) {
            $group = null;
        } elseif (is_array($group)) {
            $array  =   array();
            foreach ($group as $key => $value) {
                if (is_int($key) && !is_array($value)) {
                    $array[]    =   $value;
                } elseif (is_string($key) && !is_array($value)) {
                    $array[]    =   "`{$key}` . `{$value}`";
                } elseif (is_array($value)) {
                    $g  =   array(
                        't' => null,
                        'v' => null,
                    );
                    $value  =   array_change_key_case($value, CASE_LOWER );
                    if (isset($value['t'])) {
                        $g['t'] =   $value['t'];
                    } elseif (isset($value['table'])) {
                        $g['t'] =   $value['table'];
                    } elseif (is_string($key)) {
                        $g['t'] =   $key;
                    }
                    if (isset($value['v'])) {
                        $g['v'] =   $value['v'];
                    } elseif (isset($value['value'])) {
                        $g['v'] =   $value['value'];
                    }  elseif (isset($value['val'])) {
                        $g['v'] =   $value['val'];
                    } elseif (isset($value['f'])) {
                        $g['v'] =   $value['f'];
                    } elseif (isset($value['field'])) {
                        $g['v'] =   $value['field'];
                    }
                    $string   =     ($g['t'] != null)   ?   "`{$g['t']}` . "   :   '';
                    $string   .=    ($g['v'] != null)   ?   "`{$g['v']}`"   :   '';
                    $string   .=    ($g['a'] != null)   ?   " AS `{$g['v']}` "   :   '';
                    $array[]  =     $string;
                }
            }
            $group = implode(',', $array);
        }
        return $group;
    }

    /**
     * подготавливает значения для вставки
     * @param array $value значения
     * @param bool $forInsert длz инсерта
     * @return array
     */
    protected static function value(array $value, $forInsert = true)
    {
        $result = Array(
            'value'     => '',
            'execute'   => Array()
        );
        foreach ($value as $key => $val) {
            $v = Array(
                'a'     => null,
                'f'     => null,
                'k'     => null,
                'v '    => null,
            );
            if (isset($value['a'])) {
                $v['a'] = "`{$value['a']}` . ";
            } elseif (isset($value['as'])) {
                $v['a'] = "`{$value['as']}` . ";
            } elseif (isset($value['associate'])) {
                $v['a'] = "`{$value['associate']}` . ";
            } elseif (isset($value['alias'])) {
                $v['a'] = "`{$value['alias']}` . ";
            }
            if (isset($value['f'])) {
                $v['f'] = "`{$value['f']}`";
            } elseif (isset($value['field'])) {
                $v['f'] = "`{$value['f']}`";
            } elseif (is_string($key)) {
                $v['f'] = "`{$key}`";
            }

            if (isset($value['k'])) {
                $v['k'] = ":{$value['k']}";
            } elseif (isset($value['key'])) {
                $v['k'] = ":{$value['key']}";
            } elseif (isset($f['f'])) {
                $v['k'] = $f['f'] . uniqid();
                $v['k'] = ":{$f['k']}";
            }

            if (isset($value['v'])) {
                $v['v'] = $value['v'];
            } elseif (isset($value['value'])) {
                $v['v'] = $value['value'];
            } elseif (isset($value['val'])) {
                $v['v'] = $value['val'];
            } elseif (is_string($val)) {
                $v['v'] = $val;
            }
            $v['f'] = $v['a'] . $v['f'];
            $result['field'][$v['f']] =  $v['k'];
            $result['execute'][$v['k']] = $v['v'];
        }

        if ($forInsert == true) {
            foreach ($result['field'] as $key => $value) {
                $result['value'][] = "{$key} = {$value}";
            }
            $result['value'] = implode(',', $result['value']);
        } else {
            $f = Array();
            $v = Array();
            foreach ($result['field'] as $key => $value) {
                $f[]    =   $key;
                $v[]    =   $value;
            }
            $f = implode(',', $f);
            $v = implode(',', $v);
            $result['value'] = "({$f})VALUE({$v})";
        }
        return $result;
    }


    /**
     * Создает
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @return array
     */
    public function createGenerator($table = null, $fields = null)
    {

        $execute    =   Array();

        $sql        =   '';
        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }

    /**
     * Вставляет
     * @param mixed $table таблица
     * @param array $value поля значения
     * @return array
     */
    public function insetGenerator($table = null, $value = null)
    {
        $table      =   self::table($table);
        $value      =   self::value($value);
        $sql        =   "INSERT INTO {$table} {$value}";
        $execute    =   $value['execute'];
        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для выборки
     * @param mixed $table таблица
     * @param mixed $fields поля
     * @param mixed $where условия
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @param mixed $group группировка
     * @return array
     */
    public function selectGenerator($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null)
    {
        $execute = Array();
        $table      =   self::table($table);
        $fields     =   self::field($fields);
        $where      =   self::where($where);
        $order      =   self::order($order);
        $limit      =   self::limit($limit);
        $group      =   self::group($group);
        $sql        =   "SELECT {$fields} {$table} {$where}";
        $execute    =   array_merge($execute, $where['execute']);
        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для обновления
     * @param mixed $table таблица
     * @param array $value поля значения
     * @return array
     */
    public function updateGenerator($table = null, $value = null)
    {
        $sql        =   '';
        $execute    =   Array();


        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для удаления
     * @param mixed $table таблица
     * @param mixed $where условия
     * @return array
     */
    public function dellGenerator($table = null, $where = null)
    {
        $sql        =   '';
        $execute    =   Array();


        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для показа колонок
     * @param mixed $table таблица
     * @return array
     */
    public function columnGenerator($table = null)
    {
        $sql        =   '';
        $execute    =   Array();


        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для зачистки
     * @param mixed $table таблица
     * @return array
     */
    public function truncateGenerator($table = null)
    {
        $sql        =   '';
        $execute    =   Array();


        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


    /**
     * генерирует для удаления таблиц
     * @param mixed $table таблица
     * @return array
     */
    public function dropGenerator($table = null)
    {
        $sql        =   '';
        $execute    =   Array();


        $result = Array(
            'sql'       => $sql,
            'execute'   => $execute,
        );
        return $result;
    }


}