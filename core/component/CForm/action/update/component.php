<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace core\component\CForm\action\update;


use \core\component\{
    CForm as CForm
};


/**
 * Class component
 * @package core\component\CForm\action\update
 */
class component extends  CForm\AAction implements CForm\IAction
{


    public function run($id = 0)
    {
        $this->data['id'] = $id;
        $this->preMethod('preUpdate');
        if (!$this->isError) {
            unset($this->data['id']);
            $where = Array(
                'id' => $id
            );
            parent::$db->update(parent::$table, $this->data, $where);
        }
        $this->postMethod('postUpdate');
        $this->answer = Array(
            'result'            =>  !$this->isError,
            'data'              =>  $this->data,
            'errorData'         =>  isset($this->answer['errorData']) ?   $this->answer['errorData']    :   Array()
        );
    }


}