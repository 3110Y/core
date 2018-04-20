<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 13:33
 */

namespace Core\_view\method;


use Core\_view\{
    AMethod,
    IMethod,
    view,
    data
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
    private static $regularForEach =   '/(?<pre>.*?)\{foreach +(?<variable>[\D][\w]*) *\}(?<post>.*)/s';


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
        self::template($this->content);
    }

    private static function template(string $content): string
    {
        if(preg_match(self::$regularForEach, $content,$match)) {
            $body = self::template($match['post']);
            $pos = strpos($body, '{endforeach}');
            if ($pos === false){
                print('Syntax error endif not found for ...');
                die();
            }
        }
        $post = substr($body, $pos + 11);
        $block = substr($body, 0, $pos);    // Внутренний блок if
        return $match['pre'] . $block . ' ' . $match['output'] . $post;
    }

    private static function foreach(string $content,array $data): string
    {
        $variable   =   [];
        if(preg_match(self::$regularIf, $content,$match)) {
            if (isset($data[$match['variable']])) {
                $variable = $data[$match['variable']];
                if (\is_array($variable) && !data::isAssoc($variable)) {
                    foreach ($variable as $key => $value) {
                        foreach ($value as $k => $v) {
                            if (\is_array($value) && !data::isAssoc($value)) {
                                $newKey = "{$match['variable']}.{$k}";
                                $data[$newKey] = $value;
                            }
                        }
                    }
                }
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

                    if (data::isAssoc($variable)) {
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
        if (data::isAssoc($array)) {
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

}