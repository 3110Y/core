<?php
namespace core\component\helper;


/**
 * Trait fio
 * @package core\component\helper
 */
trait fio
{
    /**
     * Сокращение ФИО
     *
     * @param string $f
     * @param string $i
     * @param string $o
     * @return string
     */
    public static function getShortFIO(string $f = '', string $i = '', string $o = '') :string
    {
        if (!empty($f)) {
            $result = $f;
            if (!empty($i)) {
                $result .= ' ' . self::getFirstSymbol($i);
                if (!empty($o)) {
                    $result .= ' ' . self::getFirstSymbol($o);
                }
            }
        } elseif (!empty($i) && !empty($o)) {
            $result = $i . ' ' . $o;
        } else {
            $result = 'Без имени';
        }

        return $result;
    }

    /**
     * @param string $string
     * @return string
     */
    private static function getFirstSymbol(string $string = '') :string
    {
        $result = '';

        if (!empty($string)) {
            $result = mb_strtoupper(mb_substr($string, 0, 1)) . '.';
        }

        return $result;
    }
}