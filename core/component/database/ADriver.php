<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 20.4.2017
 * Time: 15:51
 */

namespace core\component\database;


/**
 * Class ADriver
 * @package core\component\database
 */
abstract class ADriver
{
    /**
     * @var array экземпляр
     */
    private static $instance = array();

    /**
     * Одиночка
     * @param array $config конфиг
     * @return object|mixed|null|object
     */
    public static function getInstance($config = array()) {
        $class  =   get_called_class();
        $key    =   md5($class. '_' . md5(serialize($config)));
        if (!isset(self::$instance[$key]) || self::$instance[$key] === null) {
            self::$instance[$key] = new $class($config);
        }
        return self::$instance[$key];
    }

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
                    $array[]    =   "`{$value}`";
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
                    $array[]    =   "`{$key}` `{$value}`";
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
                        $t['j'] =   " {$value['join']} JOIN ";
                    } else {
                        $t['j'] = '';
                    }
                    $o  =  null;
                    if (isset($value['o'])) {
                        $o =   $value['o'];
                    } elseif (isset($value['on'])) {
                        $o =   $value['on'];
                    }
                    if (is_array($o)) {
                        $o  =   self::where($o);
                        $t['o'] =  ' ON ' . $o['condition'];
                    } elseif (is_string($o)) {
                        $t['o'] =  ' ON ' . $o;
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
                    } elseif (isset($value['as'])) {
                        $t['a'] =   $value['as'];
                    } elseif (isset($value['associate'])) {
                        $t['a'] =   $value['associate'];
                    } elseif (isset($value['alias'])) {
                        $t['a'] =   $value['alias'];
                    } elseif (isset($value[1])) {
                        $t['t'] =   $value[1];
                    }
                    $string   =     ($t['j'] != null)       ?   "{$t['j']} "   :   '';
                    $string   .=    ($t['t'] != null)      ?   "`{$t['t']}` "   :   '';
                    $string   .=    ($t['a'] != null)       ?   " `{$t['a']}`"   :   '';
                    $string   .=    ($t['o'] != null)       ?   " {$t['o']} "    :   '';
                    $array[]  =     $string;
                }
            }
            $table = implode(' ', $array);
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
	    if (is_array($where)) {
		    if (empty($where)) {
			    $where = '';
		    } else {
			    $i = 0;
			    $whereArray =  $where;
			    $where = '';
			    foreach ($whereArray as $key => $value) {
				    //
				    if (($i % 2) && ($value !== 'AND' && $value !== 'OR' && $value != 'NOT')) {
					    $where .= ' AND ';
				    } else {
					    $i++;
				    }
				    if (
					    is_string($key)
					    && (
						    $value === 'CURDATE()'
						    || $value === 'CURTIME()'
						    || $value === 'NOW()'
						    || $value === '!NULL'
						    || $value === 'NULL'
						    || $value === 'IS NULL'
						    || $value === 'NOT IS NULL'
					    )
				    ) {
					    if ($value == '!NULL') {
						    $value = 'NOT IS NULL';
					    } elseif ($value == 'NULL') {
						    $value = 'IS NULL';
					    }
					    if ($value == 'IS NULL' || $value == 'NOT IS NULL') {
						    $where .= " `{$key}`  {$value} ";
					    } else {
						    $where .= " `{$key}` = {$value} ";
					    }
				    } elseif (is_string($key) && is_string($value)) {
					    preg_match("/`[a-z0-9_]+`/i", $value, $output);
					    if (isset($output[0])) {
						    $where .= " `{$key}` = {$value} ";
					    } else {
						    $k =  ":{$key}_". uniqid();
						    $where .= " `{$key}` = {$k} ";
						    $execute[$k] = $value;
					    }
				    } elseif (is_string($value)) {
					    $where .= " {$value} ";
				    } elseif (is_array($value)) {
					    if(isset($value[0]) && !isset($value['f']) && !isset($value['field']) && !is_string($key)) {
						    $tmp_where = self::where($value);
						    $execute = array_merge($execute, $tmp_where['execute']);
						    $where .= " ({$tmp_where['condition']}) ";
					    } else {

						    $w = Array(
							    't' => null,
							    'f' => null,
							    'c' => null,
							    'v' => null,
							    'k' => null,
						    );
						    if (isset($value['t'])) {
							    $w['t'] =  "`{$value['t']}` . ";
						    } elseif (isset($value['table'])) {
							    $w['t'] =  "`{$value['table']}` . ";
						    } elseif (isset($value['prefix'])) {
							    $w['t'] =  "`{$value['prefix']}` . ";
						    } elseif (isset($value['p'])) {
							    $w['t'] =  "`{$value['p']}` . ";
						    }

						    if (isset($value['f'])) {
							    $w['f'] =  $value['f'];
						    } elseif (isset($value['field'])) {
							    $w['f'] =  $value['field'];
						    } elseif (is_string($key)) {
							    $w['f'] =  $key;
						    }

						    if (isset($value['c'])) {
							    $w['c'] =  " {$value['c']} ";
						    } elseif (isset($value['condition'])) {
							    $w['c'] =  " {$value['condition']} ";
						    } elseif (isset($value['cond'])) {
							    $w['c'] =  " {$value['cond']} ";
						    } else {
							    $w['c'] =  ' = ';
						    }

						    if (isset($value['v'])) {
							    $w['v'] =  $value['v'];
						    } elseif (isset($value['val'])) {
							    $w['v'] =  $value['val'];
						    } elseif (isset($value['value'])) {
							    $w['v'] =  $value['value'];
						    }
						    if (isset($value['k'])) {
							    $w['k'] =  ":{$value['k']}_" .  uniqid();
						    } elseif (isset($value['key'])) {
							    $w['k'] =  ":{$value['key']}_" . uniqid();
						    } else {
							    $w['k'] =  ":{$w['f']}_". uniqid();
						    }
						    preg_match("/`[a-z0-9_]+`/i", $w['v'], $output);

						    if (
							    $w['v'] === 'CURDATE()'
							    || $w['v'] === 'CURTIME()'
							    || $w['v'] === 'NOW()'
							    || $w['v'] === '!NULL'
							    || $w['v'] === 'NULL'
							    || $w['v'] === 'IS NULL'
							    || $w['v'] === 'NOT IS NULL') {
							    if ($w['v'] == '!NULL') {
								    $w['v'] = 'NOT IS NULL';
							    } elseif ($w['v'] == 'NULL') {
								    $w['v'] = 'IS NULL';
							    }
							    if ($w['v'] == 'IS NULL' || $w['v'] == 'NOT IS NULL') {
								    $w['c'] = '';
							    }
							    if (!is_array($value) || !isset($value['NOT`'])) {
								    $w['f'] =   "`{$w['f']}`";
							    }
							    $where .= " {$w['t']}{$w['f']} {$w['c']} {$w['v']} ";
						    } elseif (trim(rtrim($w['c'])) === 'IN') {
							    $w['v'] =   strtr($w['v'], ' ', ',');
							    $w['v'] =   explode(',', $w['v']);
							    $w['v'] =   array_diff($w['v'], array(''));
							    if (count($w['v']) == 1) {

								    if (!is_array($value) || !isset($value['NOT`'])) {
									    $w['f'] =   "`{$w['f']}`";
								    }
								    $where .= " {$w['t']}{$w['f']} = {$w['k']} ";
								    $execute[$w['k']] = $w['v'][0];
							    } elseif(count($w['v']) == 0) {
								    if (!is_array($value) || !isset($value['NOT`'])) {
									    $w['f'] =   "`{$w['f']}`";
								    }
								    $where .= " {$w['t']}{$w['f']} IS NULL AND {$w['t']}`{$w['f']}` = '1' ";
							    } else {
								    $keys = Array();
								    foreach ($w['v'] as $kW =>$v) {
									    $k = ":{$w['k']}_" . uniqid() . $kW;
									    $execute[$k] = $v;
									    $keys[] = $k;
								    }
								    $keys = implode(',', $keys);
								    if (!is_array($value) || !isset($value['NOT`'])) {
									    $w['f'] =   "`{$w['f']}`";
								    }
								    $where .= " {$w['t']}{$w['f']} {$w['c']} ({$keys}) ";
							    }
						    } elseif (isset($output[0])) {
							    if (!is_array($value) || !isset($value['NOT`'])) {
								    $w['f'] =   "`{$w['f']}`";
							    }
							    $where .= " {$w['t']}{$w['f']} {$w['c']} {$w['v']} ";
						    } else {

							    if (!is_array($value) || !isset($value['NOT`'])) {
								    $w['f'] =   "`{$w['f']}`";
							    }
							    $where .= " {$w['t']}{$w['f']} {$w['c']} {$w['k']} ";
							    $execute[$w['k']] = $w['v'];
						    }


					    }
				    } else {
					    $k =  ":{$key}_". uniqid();
					    $where .= " `{$key}` = {$k} ";
					    $execute[$k] = $value;
				    }
			    }
		    }
	    }
	    $result = Array(
		    'condition'  =>  $where,
		    'where'     =>  ($where != null && $where != '')    ?   " WHERE {$where} "  : '',
		    'execute'   =>  $execute
	    );

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
                } elseif (is_string($key) && !is_array($value) && ( $value == 'ASC' || $value == 'DESC' )) {
	                $array[]    =   "`{$key}` {$value}";
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
        $order  =   ($order !== null && $order !== '')  ?   " ORDER BY  {$order} " :   '';
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
                $l['t'] =   $limit['to'];
            } elseif (isset($limit[1])) {
                $l['t'] =   $limit[1];
            }
            $limit = implode(',', $l);
        }
        $limit  =   ($limit !== null && $limit !== '')  ?   " LIMIT {$limit} " :   '';
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
        $group  =   ($group !== null && $group !== '')  ?   " GROUP BY {$group} " :   '';
        return $group;
    }

    /**
     * подготавливает значения для вставки
     * @param array $value значения
     * @param bool $forInsert длz инсерта
     * @return array
     */
    protected static function value(array $value, $forInsert = true): array
    {
        $result = Array(
            'value'     => '',
            'execute'   => Array()
        );
        $value  =   array_change_key_case($value, CASE_LOWER );
        foreach ($value as $key => $val) {
            if (is_array($val)) {
                $val = array_change_key_case($val, CASE_LOWER);
            }
            $v = Array(
                'a'     => null,
                'f'     => null,
                'k'     => null,
                'v'     => null,
            );
            if (isset($val['a'])) {
                $v['a'] = "`{$val['a']}` . ";
            } elseif (isset($val['as'])) {
                $v['a'] = "`{$val['as']}` . ";
            } elseif (isset($val['associate'])) {
                $v['a'] = "`{$val['associate']}` . ";
            } elseif (isset($val['alias'])) {
                $v['a'] = "`{$val['alias']}` . ";
            }
            if (isset($val['f'])) {
                $v['f'] = $val['f'];
            } elseif (isset($val['field'])) {
                $v['f'] = $val['f'];
            } elseif (is_string($key)) {
                $v['f'] = $key;
            }

            if (isset($val['k'])) {
                $v['k'] = ":{$val['k']}";
            } elseif (isset($val['key'])) {
                $v['k'] = ":{$val['key']}";
            } elseif ($v['f'] !== null) {
                $v['k'] = $v['f'] . '_' . uniqid();
                $v['k'] = ":{$v['k']}";
            }

            if (isset($val['v'])) {
                $v['v'] = $val['v'];
            } elseif (isset($val['value'])) {
                $v['v'] = $val['value'];
            } elseif (isset($val['val'])) {
                $v['v'] = $val['val'];
            } elseif (!is_array($val)) {
                $v['v'] = $val;
            }
            if ($v['f'] !== null) {
                $v['f'] =   " `{$v['f']}` ";
            }
            $v['f'] = $v['a'] . $v['f'];
            $result['field'][$v['f']] =  $v['k'];
            $result['execute'][$v['k']] = $v['v'];
        }
        if ($forInsert === false) {
            if (isset($result['field']) && is_array($result['field'])) {
	            $result['value'] = Array();
                foreach ($result['field'] as $key => $val) {
                    $result['value'][] = "{$key} = {$val}";
                }
                $result['value'] = implode(',', $result['value']);
            }
        } else {
            $f = Array();
            $v = Array();
            if (isset($result['field']) && is_array($result['field'])) {
                foreach ($result['field'] as $key => $val) {
                    $f[] = $key;
                    $v[] = $val;
                }
            }
            $f = implode(',', $f);
            $v = implode(',', $v);
            $result['value'] = "({$f})VALUE({$v})";
        }
        return $result;
    }

    /**
     * подготавливает поля для создания
     * @param array $field значения
     * @return string
     */
    protected static function fieldCreate(array $field)
    {
        $field  =   array_change_key_case($field, CASE_LOWER );
        $array = array();
        $primary = Array();
        foreach ($field as $key => $val) {
            $val  =   array_change_key_case($val, CASE_LOWER );
            $v = Array(
                'f'     => null,
                't'     => null,
                'l'     => null,
                'd '    => null,
                'a'     => null,
                'i'     => null,
                'ai'    => null,
                'c'     => null,
            );
            if (isset($val['f'])) {
                $v['f'] =   $val['f'];
            } elseif (isset($val['fields'])) {
                $v['f'] =   $val['fields'];
            } elseif (is_string($key)) {
                $v['f'] =   $key;
            } elseif (isset($val[0])) {
                $v['f'] =   $key;
            }
            if (isset($val['t'])) {
                $v['t'] =   $val['t'];
            } elseif (isset($val['type'])) {
                $v['t'] =   $val['type'];
            } elseif (isset($val[1])) {
                $v['t'] =   $val[1];
            }
            if (isset($val['l'])) {
                $v['l'] =   $val['l'];
            } elseif (isset($val['length'])) {
                $v['l'] =   $val['length'];
            } elseif (isset($val[2])) {
                $v['l'] =   $val[2];
            }
            if (isset($val['d'])) {
                $v['d'] =   $val['d'];
            } elseif (isset($val['default'])) {
                $v['d'] =   $val['default'];
            } else {
                $v['d'] =   'NULL';
            }
            if (isset($val['a'])) {
                $v['a'] =   $val['a'];
            } elseif (isset($val['attributes'])) {
                $v['a'] =   $val['attributes'];
            } elseif (isset($val['attr'])) {
                $v['a'] =   $val['attr'];
            } elseif (isset($val['extra'])) {
                $v['a'] =   $val['extra'];
            }
            if (isset($val['i'])) {
                $v['i'] =   $val['i'];
            } elseif (isset($val['index'])) {
                $v['i'] =   $val['index'];
            }
            if (isset($val['ai'])) {
                $v['ai'] =   $val['ai'];
            } elseif (isset($val['AUTO_INCREMENT'])) {
                $v['ai'] =   $val['AUTO_INCREMENT'];
            }
            if(isset($v['ai']) && $v['ai']) {
                $v['ai'] = 'AUTO_INCREMENT';
                $primary[]  =   $v['f'];
            }
            if (isset($val['c'])) {
                $v['c'] =   $val['c'];
            } elseif (isset($val['comments'])) {
                $v['c'] =   $val['comments'];
            }
            if (isset($v['l'])) {
                $v['t'] = "{$v['t']}({$v['l']})";
            }
            $v['d'] =   "DEFAULT {$v['d']}";
            $array[]  =   "{$v['f']} {$v['t']} {$v['d']} {$v['a']} {$v['i']} {$v['ai']}";
        }
        if (!empty($primary)) {
            $key    =   implode(',', $primary);
            $array[] = "PRIMARY KEY({$key})";
        }
        $value  =   implode(',', $array);
        return $value;
    }

    /**
     * Создает
     * @param mixed $table таблица
     * @param array $fields поля
     * @return array
     */
    public function createGenerator($table = null, $fields = null)
    {
        //todo: запрос
        $execute    =   Array();
        $table      =   self::table($table);
        $fields     =   self::fieldCreate($fields);
        $sql        =   "CREATE TABLE IF NOT EXISTS {$table} ({$fields})";
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
    public function insetGenerator($table = null, $value = null): array
    {
        $table      =   self::table($table);
        $value      =   self::value($value);
        $sql        =   "INSERT INTO {$table} {$value['value']}";
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
     * @param mixed $having указание условий в результах агрегатных функций
     * @return array
     */
    public function selectGenerator($table = null, $fields = null, $where = null, $order = null, $limit = null, $group = null, $having = null)
    {
        $execute = Array();
        $table      =   self::table($table);
        $fields     =   self::field($fields);
        $where      =   self::where($where);
        $order      =   self::order($order);
        $limit      =   self::limit($limit);
        $group      =   self::group($group);
        $having     =   self::where($having);
        $sql        =   "SELECT {$fields} FROM {$table} {$where['where']} {$group} {$having['where']} {$order} {$limit}";
        $execute    =   array_merge($execute, $where['execute']);
        $execute    =   array_merge($execute, $having['execute']);
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
     * @param mixed $where условия
     * @return array
     */
    public function updateGenerator($table = null, $value = null, $where = null)
    {
        $execute = Array();
        $table      =   self::table($table);
        $value      =   self::value($value, false);
        $where      =   self::where($where);
        $sql        =   "UPDATE {$table} SET {$value['value']} {$where['where']} ";
        $execute    =   array_merge($execute, $value['execute']);
        $execute    =   array_merge($execute, $where['execute']);
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
     * @param mixed $order порядок
     * @param mixed $limit лимит
     * @return array
     */
    public function dellGenerator($table = null, $where = null, $order = null, $limit = null)
    {
        $table      =   self::table($table);
        $where      =   self::where($where);
        $order      =   self::order($order);
        $limit      =   self::limit($limit);
        $sql        =   "DELETE FROM {$table} {$where['where']} {$order} {$limit}";
        $execute    =   $where['execute'];
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
        $table      =   self::table($table);
        $sql        =   "SHOW COLUMNS {$table} ";
        $result = Array(
            'sql'       => $sql,
            'execute'   => Array(),
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
        $table      =   self::table($table);
        $sql        =   "TRUNCATE TABLE {$table}";
        $result = Array(
            'sql'       => $sql,
            'execute'   => Array(),
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
        $table      =   self::table($table);
        $sql        =   "DROP TABLE {$table}";
        $result = Array(
            'sql'       => $sql,
            'execute'   => Array(),
        );
        return $result;
    }


}