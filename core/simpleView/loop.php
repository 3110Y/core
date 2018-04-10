<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 10.04.18
 * Time: 18:35
 */

namespace core\simpleView;


class loop
{

    /**
     * Переберает шаблоны
     *
     * @param $tagEach
     * @param array $array
     * @param string $html
     * @return string
     */
    public static function render($html = '', $tagEach, array $array) : string
    {
        $cuteFragment = template::cutAll($tagEach, $html);
        for($i = 0, $iMax = \count($cuteFragment);$i < $iMax; $i++) {
            if (self::isAssoc($array)) {
                $cuteResult = self::loopAssoc($cuteFragment[$i], $array);
            } else {
                $cuteResult = self::loopSequential($cuteFragment[$i], $array);
            }
            $cuteResult =   implode(PHP_EOL, $cuteResult);
            $html = preg_replace("/{{$tagEach}}.*?{\\/{$tagEach}}/is", $cuteResult, $html, 1);
        }
        return $html;
    }

    /**
     * Переберает шаблоны
     * @param array $array массив значений
     * @param string $cuteFragment
     * @return array
     */
    private static function loopSequential( string $cuteFragment, array $array): array
    {
        $cuteResult = array();
        if (\count($array) > 0) {
            foreach ($array as $key => $value) {
                if (\is_array($value)) {
                    $cuteResult[] = simpleView::replace($cuteFragment, $value);
                }
            }
        }
        return $cuteResult;
    }

    /**
     * @param array $array
     * @param string $cuteFragment
     * @return array
     */
    private static function loopAssoc( string $cuteFragment, array $array): array
    {
        $cuteResult = array();
        if (\count($array) > 0) {
            $cuteResult[] = simpleView::replace($cuteFragment, $array);
        }
        return $cuteResult;
    }


    /**
     * Отдает фрагмент
     * @param $array
     * @return bool результат
     */
    private static function isAssoc($array): bool
    {
        $key    =   array_keys($array);
        return array_keys($key) !== $key;
    }
}