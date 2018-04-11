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
    IMethod
};


/**
 * Class replace
 * @package core\view\method
 */
class condition extends AMethod implements IMethod
{

    /**
     * @var array
     */
    private $ifCondition    =   [];


    /**
     * @return mixed
     */
    public function prepareData(): void
    {
        foreach ($this->data as $key    =>  $value) {
            if (\is_bool($value)) {
                $this->ifCondition[$key]    =   $value;
            }
        }
    }

    /**
     * @return mixed
     */
    public function prepareTemplate(): void
    {
        // TODO: Implement prepareTemplate() method.
    }
}