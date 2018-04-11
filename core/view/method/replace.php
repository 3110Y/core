<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 14:25
 */

namespace core\view\method;


use core\view\{
    AMethod,
    IMethod
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
            if (\is_string($value) || \is_int($value) || \is_float($value)) {
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