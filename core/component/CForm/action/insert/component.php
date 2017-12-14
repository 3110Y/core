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
        $this->data['date_insert']  =   date('Y-m-d H:i:s');
        if (!$this->isError) {
            var_dump(parent::$db->insetGenerator(parent::$table, $this->data));
            parent::$db->inset(parent::$table, $this->data);
            $id = parent::$db->getLastID();
        } else {
            $id = 0;
        }
        var_dump($id);
        die();
        $this->postMethod('postInsert');
        $this->answer = $this->isError;
        if (isset($_GET['redirect'])) {
            self::redirect($_GET['redirect'] . $id);
        }
    }


}