<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\component\CForm\action\delete;


use \core\component\{
    CForm as CForm
};


/**
 * Class component
 * @package core\component\CForm\action\delete
 */
class component extends  CForm\AAction implements CForm\IAction
{


    public function run($id = 0)
    {
        $this->preMethod('preDelete');
        if (!$this->isError) {
            $where = Array(
                'id' => $id
            );
            parent::$db->dell(parent::$table, $where);
        }
        $this->postMethod('postDelete');
        if (isset($_GET['return'])) {
            self::redirect($_GET['rederect']);
        }

    }


    public function many($id = 0)
    {
        die();
    }


}