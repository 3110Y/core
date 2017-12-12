<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\component\CForm\action\save;


use \core\component\{
    CForm as CForm
};


/**
 * Class component
 * @package core\component\CForm\action\save
 */
class component extends  CForm\AAction implements CForm\IAction
{


    public function run($id = 0)
    {
        $this->preMethod('preUpdate');
        if ($this->isError) {
            $where = Array(
                'id' => $id
            );
            parent::$db->update(parent::$table, $this->data, $where);
        }
        $this->postMethod('postUpdate');
        $this->answer = $this->isError;
    }


}