<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 11.04.18
 * Time: 13:32
 */

namespace core\view\method;


use core\view\{
    AMethod,
    IMethod,
    template
};


/**
 * Class replace
 * @package core\view\method
 */
class contain extends AMethod implements IMethod
{

    /**
     * @return mixed
     */
    public function prepareData(): void
    {}

    /**
     * @return mixed
     */
    public function prepareTemplate(): void
    {
        $path = template::getPath($this->template);
        $array = Array();
        preg_match_all("/{include ['\"]?([a-z0-9\\/.\\-_]+)['\"]?}/i", $this->content, $output);
        if (!empty($output[1])) {
            for ($i = 0, $iMax = \count($output[1]); $i < $iMax; $i++) {
                $file = $path . $output[1][$i];
                $array[$output[0][$i]] = template::toHTML($file);
            }
            $this->content = strtr($this->content, $array);
        }
    }
}