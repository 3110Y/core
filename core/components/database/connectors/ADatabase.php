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
    protected function field($field)
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
    protected function table($table)
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
                        $o  =   $this->where($o);
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
    protected function where($where)
    {
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
                    $tmp_where = $this->where($value);
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

        return $result;
    }

    /**
     * подготавливает сортировку
     * @param mixed $order
     * @return string
     */
    protected function order($order)
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
    protected function limit($limit)
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
    protected function group($group)
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

}