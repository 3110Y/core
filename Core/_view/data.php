<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.04.18
 * Time: 16:54
 */

namespace Core\_view;


class data
{

    /**
     * Отдает фрагмент
     * @param $array
     * @return bool результат
     */
    public static function isAssoc($array): bool
    {
        $key    =   array_keys($array);
        return array_keys($key) !== $key;
    }
}