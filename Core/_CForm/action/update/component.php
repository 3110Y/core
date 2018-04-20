<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 12.12.2017
 * Time: 14:14
 */

namespace Core\_CForm\action\update;


use \Core\{
    _CForm as CForm
};


/**
 * Class component
 * @package core\CForm\action\update
 */
class component extends  _CForm\AAction implements _CForm\IAction
{

    /**
     * @param int $id
     * @return mixed|void
     */
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
            $this->data['id'] = $id;
            $this->postMethod('postUpdate');
            unset($this->data['id']);
        }

        $this->answer = Array(
            'result'            =>  !$this->isError,
            'data'              =>  $this->data,
            'errorData'         =>  isset($this->answer['errorData']) ?   $this->answer['errorData']    :   Array()
        );
    }


}