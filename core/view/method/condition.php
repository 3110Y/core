<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 13:33
 */

namespace core\view\method;


use core\view\{
    AMethod, IMethod, view
};


/**
 * Class replace
 * @package core\view\method
 */
class condition extends AMethod implements IMethod
{
    /**
     * @var string
     */
    private static $regularIf =   '/(?<pre>.*?)\{if +(?<p1>[\D][\w]*) *(?<op>[=!><]+) *(?<p2>.*?) *\}(?<post>.*)/s';

    /**
     * @var string
     */
    private static $regularElse =   '/(.*?)\{else\}(.*)/s';


    /**
     * @return void
     */
    public function prepareData(): void
    {}

    /**
     * @return void
     */
    public function prepareTemplate(): void
    {}

    public function render(): void
    {
        $this->content  = self::if($this->content, $this->data);
    }

    private static function if($content, $data)
    {
        if(preg_match(self::$regularIf, $content,$match)) {

            $textIsYes = view::view('', $data, $match['post'])->render();
            $pos = strpos($textIsYes, '{endif}');
            if ($pos === false){
                print('Syntax error endif not found for ...');
                die();
            }
            $post = substr($textIsYes, $pos + 7); // $post - текст после endif
            $textIsYes = substr($textIsYes, 0, $pos);    // Внутренний блок if
            $textIsNo = '';
            if (preg_match(self::$regularElse, $textIsYes, $m2)){
                $textIsYes = $m2[1];
                $textIsNo = $m2[2];
            }
            // Обрабатываем параметры, обработка конечно должна быть более развернутой, с проверкой
            // на существование переменных, на то а переменные ли это или константы
            $p1 = $data[$match['p1']] ?? $match['p1'];
            $p2 = $data[$match['p2']] ?? $match['p2'];
            switch ($match['op']) {
                // Если условие не прошло, то заменяем текст $textIsYes на блок {else}, если был или пустоту
                case '===':
                    if ($p1 !== $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '==':
                    if ($p1 != $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '!=':
                    if ($p1 == $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '!==':
                    if ($p1 !== $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '<':
                    if ($p1 < $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '>':
                    if ($p1 > $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '<=':
                    if ($p1 <= $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '>=':
                    if ($p1 >= $p2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
            }
            return $match['pre'] . $textIsYes . $post;
        }
        return $content;
    }
}