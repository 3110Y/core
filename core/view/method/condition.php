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
    private static $regularIf =   '/(?<pre>.*?)\{if +(?<var1>[\D][\w]*) *(?<cond>[=!><]+) *(?<var2>.*?) *\}(?<post>.*)/s';

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
            $var1 = self::prepareVariable($data[$match['var1']] ?? $match['var1']);
            $var2 = self::prepareVariable($data[$match['var2']] ?? $match['var2']);
            switch ($match['cond']) {
                // Если условие не прошло, то заменяем текст $textIsYes на блок {else}, если был или пустоту
                case '===':
                    if ($var1 !== $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '==':
                    if ($var1 != $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '!=':
                    if ($var1 == $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '!==':
                    if ($var1 !== $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '<':
                    if ($var1 < $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '>':
                    if ($var1 > $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '<=':
                    if ($var1 <= $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
                case '>=':
                    if ($var1 >= $var2) {
                        $textIsYes = $textIsNo;
                    }
                    break;
            }
            return view::view('', $data, $match['pre'] . $textIsYes . $post)->render();
        }
        return $content;
    }

    /**
     * @param mixed $variable
     * @return array|bool|string
     */
    private static function prepareVariable($variable)
    {
        switch ($variable) {
            case 'true':
                $variable   =   true;
                break;
            case 'false':
                $variable   =   false;
                break;
            case 'null':
                $variable   =   '';
                break;
            case '[]':
                $variable   =   [];
                break;
        }
        return $variable;
    }

}