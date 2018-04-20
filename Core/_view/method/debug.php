<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 17:03
 */

namespace Core\_view\method;


use Core\_view\{
    AMethod,
    IMethod
};


/**
 * Class debug
 * @package core\view\method
 */
class debug extends AMethod implements IMethod
{
    /**
     * @var array
     */
    private static $dataDebug = [];


    /**
     * @return mixed
     */
    public function prepareData(): void
    {
        $key    =   md5(serialize($this->data));
        if (isset(self::$dataDebug[$key])) {
            $array['{DEBUG}'] = self::$dataDebug[$key];
        }
        $this->data['{DEBUG}']  = print_r(self::htmlEntitiesArray($this->data), true);
        self::$dataDebug[$key]  =  $this->data;
    }

    /**
     * @return mixed
     */
    public function prepareTemplate(): void
    {}

    /**
     * @param $data
     * @return array|string
     */
    private static function htmlEntitiesArray($data)
    {
        if (\is_array($data)) {
            $array = [];
            foreach ($data as $key => $value) {
                $array[$key] = self::htmlEntitiesArray($value);
            }
            return $array;
        }
        if (\is_string($data)) {
            return htmlentities($data);
        }
        return $data;

    }
}