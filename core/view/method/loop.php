<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 13:33
 */

namespace core\view\method;


use core\view\{
    AMethod,
    IMethod,
    view
};


/**
 * Class replace
 * @package core\view\method
 */
class loop extends AMethod implements IMethod
{
    /**
     * @var string
     */
    private static $regularIf =   '/(?<pre>.*?)\{foreach +(?<variable>[\D][\w]*) *\}(?<post>.*)/s';


    /**
     * @return mixed
     */
    public function prepareData(): void
    {
        // TODO: Implement prepareData() method.
    }

    /**
     * @return mixed
     */
    public function prepareTemplate(): void
    {
        // TODO: Implement prepareTemplate() method.
    }

    private static function foreach($content, $data)
    {
        if(preg_match(self::$regularIf, $content,$match)) {

            foreach ($data as $key  =>  $value) {

            }
            $body = view::view('', $data, $match['post'])->render();
            $pos = strpos($body, '{endforeach}');
            if ($pos === false){
                print('Syntax error endif not found for ...');
                die();
            }
            $post = substr($body, $pos + 7);
            $body = substr($body, 0, $pos);


            if (isset($data[$match['variable']])) {
                $variable   = $data[$match['variable']] ?? $match['variable'];
                if (\is_array($variable)) {

                    if (self::isAssoc($variable)) {
                        foreach ($variable as $k => $value) {
                            $newKey = "{$match['variable']}.{$k}";
                            $data[$newKey] = self::arrayToVariable($newKey, $value);
                        }
                    } else {

                    }
                }


                $array      =   self::arrayToVariable($match['variable'], $variable);
                if (\is_array($array)) {
                    $array = array_merge($data, $array);
                } else {
                    $array = $data;
                }

                $textIsYes = view::view('', $array, $match['post'])->render();

            } else {
                $textIsYes = '';
            }



            return $match['pre'] . $textIsYes . $post;
        }
        return $content;
    }

    /**
     * @param string $key
     * @param array $array
     * @return mixed
     */
    private static function arrayToVariable(string $key, $array)
    {
        $data = [];
        if (\is_array($array)) {
            return $array;
        }
        if (self::isAssoc($array)) {
            foreach ($array as $k => $value) {
                $newKey = "{$key}.{$k}";
                $data[$newKey] = self::arrayToVariable($newKey, $value);
            }
            return $data;
        }
        foreach ($array as $k => $value) {
            $data[$key] = self::arrayToVariable($key, $value);
        }
        return $data;
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