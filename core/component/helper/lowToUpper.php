<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 15.02.18
 * Time: 22:11
 */

namespace core\component\helper;

/**
 * Trait lowToUpper
 * @package core\helper
 */
trait lowToUpper
{
    /**
     * Перевод ключей массива в верхний регистр
     *
     * @param array $array входящий массив
     *
     * @access public
     * @static
     *
     * @return array исходящий массив
     */
    public static function lowToUpper($array) : array
    {
        $arrayNew = [];

        if (!empty($array) && \is_array($array)) {
            foreach ($array as $key => $value) {
                if (\is_array($value)) {
                    $value = self::lowToUpper($value);
                }
                $arrayNew[mb_strtoupper($key)] = $value;
            }
        }

        return $arrayNew;
    }

    /**
     * Перевод ключей массива в нижний регистр
     *
     * @param array $array входящий массив
     *
     * @access public
     * @static
     *
     * @return array исходящий массив
     */
    public static function upperToLow($array) : array
    {
        $arrayNew = [];

        if (!empty($array) && \is_array($array)) {
            foreach ($array as $key => $value) {
                if (\is_array($value)) {
                    $value = self::upperToLow($value);
                }
                $arrayNew[mb_strtolower($key)] = $value;
            }
        }

        return $arrayNew;
    }


}