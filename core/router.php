<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 12:21
 */

namespace core;

/**
 * Class router Роутер ядра
 * @package core
 */
class router
{
    public function getAppRouter()
    {
        return new \app\router();
    }
}