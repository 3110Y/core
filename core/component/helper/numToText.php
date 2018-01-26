<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 26.01.2018
 * Time: 4:12
 */

namespace core\component\helper;


/**
 * Class numToText
 * @package application\expo\model
 */
trait numToText
{

    /**
     * @var string
     */
    private static $null = 'ноль';
    /**
     * @var array
     */
    private static $ten =  [
        [
            '',
            'один',
            'два',
            'три',
            'четыре',
            'пять',
            'шесть',
            'семь',
            'восемь',
            'девять'],
        [
            '',
            'одна',
            'две',
            'три',
            'четыре',
            'пять',
            'шесть',
            'семь',
            'восемь',
            'девять'
        ],
    ];
    /**
     * @var array
     */
    private static $eleven    =  [
        'десять',
        'одиннадцать',
        'двенадцать',
        'тринадцать',
        'четырнадцать',
        'пятнадцать',
        'шестнадцать',
        'семнадцать',
        'восемнадцать',
        'девятнадцать'
    ];
    /**
     * @var array
     */
    private static $tens    =   [
        2=>'двадцать',
        'тридцать',
        'сорок',
        'пятьдесят',
        'шестьдесят',
        'семьдесят' ,
        'восемьдесят',
        'девяносто'
    ];
    /**
     * @var array
     */
    private static $hundred =   [
        '',
        'сто',
        'двести',
        'триста',
        'четыреста',
        'пятьсот',
        'шестьсот',
        'семьсот',
        'восемьсот',
        'девятьсот'
    ];
    /**
     * @var array
     */
    private static  $unit   =  [
        [
            'копейка',
            'копейки',
            'копеек',
            1
        ],
        [
            'рубль',
            'рубля',
            'рублей',
            0
        ],
        [
            'тысяча',
            'тысячи',
            'тысяч',
            1
        ],
        [
            'миллион',
            'миллиона',
            'миллионов',
            0
        ],
        [
            'миллиард',
            'милиарда',
            'миллиардов',
            0
        ],
    ];

    /**
     * Преобразует Цифры в текст
     * @param int $num
     * @return string
     */
    public static function num2str(int $num) : string
    {
        list($rub,$kop) = explode('.',  sprintf("%015.2f", floatval($num)));
        $out = array();
        if ((int)$rub   >   0) {
            foreach(str_split($rub,3) as $uk    =>  $v) {
                if (!intval($v)) {
                    continue;
                }
                $uk     =   sizeof((self::$unit)-$uk-1); // unit key
                $gender =   self::$unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                $out[] = self::$hundred[$i1];
                if ($i2 >   1) {
                    $out[]= self::$tens[$i2]    .   ' ' .   self::$ten[$gender][$i3];
                } else {
                    $out[] = $i2>0 ? self::$eleven[$i3] : self::$ten[$gender][$i3];
                }
                // units without rub & kop
                if ($uk>1) {
                    $out[]= self::morph($v, self::$unit[$uk][0], self::$unit[$uk][1], self::$unit[$uk][2]);
                }
            } //foreach
        } else {
            $out[] = self::$null;
        }
        $out[] = self::morph(intval($rub), self::$unit[1][0], self::$unit[1][1], self::$unit[1][2]);
        $out[] = $kop . ' ' . self::morph($kop, self::$unit[0][0], self::$unit[0][1], self::$unit[0][2]);
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    /**
     * Склоняем словоформу
     * @author runcore
     * @param string $n
     * @param string $f1
     * @param string $f2
     * @param string $f5
     * @return string mixed
     */
    private static function morph(string $n, string $f1, string $f2, string $f5)  : string {
        $n = abs(intval($n)) % 100;
        if ($n  >   10 && $n    <   20){
            return $f5;
        }
        $n = $n % 10;
        if ($n>1 && $n<5) {
            return $f2;
        }
        if ($n==1) {
            return $f1;
        }
        return $f5;
    }
}