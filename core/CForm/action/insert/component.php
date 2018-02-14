<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\CForm\action\insert;


use \core\{
    CForm as CForm
};


/**
 * Class component
 * @package core\CForm\action\insert
 */
class component extends  CForm\AAction implements CForm\IAction
{

    /**
     * @param int $id
     * @return mixed|void
     */
    public function run($id = 0)
    {
        $this->preMethod('preInsert');
        $this->data['date_insert']  =   date('Y-m-d H:i:s');
        $this->data['parent_id']    =   parent::$id;
        if (!$this->isError) {
            parent::$db->inset(parent::$table, $this->data);
            $id = parent::$db->getLastID();
            $this->data['id'] = $id;
            $this->postMethod('postInsert');
            unset($this->data['id']);
        } else {
            $id = 0;
        }

        $this->answer = $this->isError;
        if (isset($_GET['redirect'])) {
            self::redirect($_GET['redirect'] . $id);
        }
    }


}