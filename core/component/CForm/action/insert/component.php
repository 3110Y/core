<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\component\CForm\action\insert;


use \core\component\{
    CForm as CForm
};


/**
 * Class component
 * @package core\component\CForm\action\insert
 */
class component extends  CForm\AAction implements CForm\IAction
{


    public function run($id = 0)
    {
        $this->preMethod('preInsert');
        if (!$this->isError) {
            $where = Array(
                'id' => $id
            );
            parent::$db->inset(parent::$table, $this->data);
        }
        $this->postMethod('postInsert');
        $this->answer = $this->isError;
        if (isset($_GET['rederect'])) {
            self::redirect($_GET['rederect'] . parent::$db->getLastID());
        }
    }


}