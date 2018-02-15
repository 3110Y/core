<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 15.02.18
 * Time: 22:11
 */

namespace core\helper;

/**
 * Trait lowToUpper
 * @package core\helper
 */
trait lowToUpper
{
    /**
     * Превращает маленькие в большие с любыми ключами
     *
     * @param array $array входящий массив
     *
     * @access public
     * @static
     *
     * @return array исходящий массив с большими ключами
     */
    private static function lowToUpper(array $array = Array()) : array
    {
        $arrayNew = [];
        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $value = self::lowToUpper($value);
            }
            $arrayNew[mb_strtoupper($key)] = $value;
        }
        return $arrayNew;
    }
}