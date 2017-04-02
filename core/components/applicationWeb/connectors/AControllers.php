<?php
/**
 * Created by IntelliJ IDEA.
 * User: Guy
 * Date: 20.03.2017
 * Time: 16:04
 */

namespace core\components\applicationWeb\connectors;

/**
 * Class controllers
 * @package core\connectors\app
 */
abstract class AControllers
{

    /**
     * Отдает структуру контента
     * @return array структура контента
     */
    public function getContent()
    {
        return $this->content;
    }
}
