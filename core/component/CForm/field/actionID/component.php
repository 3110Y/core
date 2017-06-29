<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 28.05.17
 * Time: 16:16
 */

namespace core\component\CForm\field\actionID;

use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView as simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\actionID
 */
class component extends CForm\AField implements CForm\IField
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.0;


	public function init()
    {
        $id     =   self::$data['id'];
        $this->addAnswerID("input-field-actionID-id-{$id}");
        $this->addAnswerClass('actionID');
	    self::$config['controller']::setCss(self::getTemplate('css/actionID.css', __DIR__));
	    self::$config['controller']::setJs(self::getTemplate('js/actionID.js', __DIR__));
    }

    public function run()
    {

    }

    public function edit()
    {
        $data   =   Array();
        foreach (self::$data as $field  => $value) {
            $data['DATA_' . mb_strtoupper($field)] = $value;
        }
        $answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
        $answer =   simpleView\component::replace(self::getTemplate('tpl/listingCaption.tpl', __DIR__), $data);
        $this->setFieldCaption($answer);
    }
}