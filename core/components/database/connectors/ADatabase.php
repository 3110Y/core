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
                if (is_int($key)    &&  is_string($value)) {
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

                }
            }
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
                    $where['where'] .= ' AND ';
                    $i++;
                }
                if(is_int($key) && is_array($value)) {
                    $tmp_where = $this->where($value);
                    $where['execute']   =       array_merge($where['execute'], $tmp_where['execute']);
                    $where['where']     .=      "({$tmp_where['sql']})";
                } elseif(is_int($key) && !is_array($value)) {
                    $where['where'] .= " {$value } ";
                } elseif(is_array($value)) {
                    $value['condition']             =   (isset($value['condition']))   ?   $value['condition']        :   '=';
                    $prefix                         =   (isset($value['prefix']))       ?   " `{$value['prefix']}`."  :   '';
                    if(!is_array($value['value']))
                    {
                        $where['sql']                   .=  $prefix . '`'.$key.'` '.(($value['condition'] == 'IN') ?   $value['condition'].' (:'.$key.')'  :   $value['condition'].' :'.$key);
                        $where['execute'][':'.$key]     =   $value['value'];
                    }
                    elseif(is_array($value['value']) && $value['condition']  == 'IN' )
                    {
                        $keyArray = Array();
                        for($i=0;$i<count($value['value']);$i++)
                        {
                            $where['execute'][':'.$key.$i]     =   $value['value'][$i];
                            $keyArray[]   = ':'.$key.$i;
                        }
                        $where['sql']                   .= $prefix.'`'.$key.'` '.$value['condition'].' ('.implode(",",$keyArray).')';
                    }
                } else {
                    $where['where']                   .=  '`'.$key.'` = :'.$key;
                    $where['execute'][':'.$key]     =   $value;
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
        return $order;
    }

    /**
     * подготавливает лимит
     * @param mixed $limit
     * @return string
     */
    protected function limit($limit)
    {
        return $limit;
    }

    /**
     * подготавливает группировку
     * @param mixed $group
     * @return string
     */
    protected function group($group)
    {
        return $group;
    }

}