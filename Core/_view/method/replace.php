<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 14:25
 */

namespace Core\_view\method;


use Core\_view\{
    AMethod,
    IMethod,
    data
};


/**
 * Class replace
 * @package core\view\method
 */
class replace extends AMethod implements IMethod
{

    /**
     * @return mixed
     */
    public function prepareData(): void
    {
        $array = [];
        foreach ($this->data as $key => $value) {
            if (\is_array($value)) {
                if (data::isAssoc($value)) {
                    foreach ($value as $k => $v) {
                        $newKey =   "{$key}.{$k}";
                        $array["{\${$newKey}}"] = $v;
                        $array["{{$newKey}}"] = $v;
                    }
                }
            } else {
                $array["{\${$key}}"] = $value;
                $array["{{$key}}"] = $value;
            }
        }
        $this->content = strtr($this->content, $array);
        $this->content = preg_replace("/(.*?){\$[\w]+}(.*?)/im", '$1', $this->content);
    }

    /**
     * @return mixed
     */
    public function prepareTemplate(): void
    {}

}