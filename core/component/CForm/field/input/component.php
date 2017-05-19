<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 13:37
 */

namespace core\component\CForm\field\input;

use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};

/**
 * Class component
 *
 * @package core\component\CForm\field\input
 */
class component extends CForm\AField implements CForm\IField
{

	public function init()
	{
		$field  =   $this->componentSchema['field'];
		$id     =   self::$data['id'];
		$this->addAnswerID("input-field-{$field}-id-{$id}");
		$this->addAnswerClass('input');
		self::setCss(self::getTemplate('css/input.css', __DIR__));
	}

	public function run()
	{

	}

	/**
	 * генирирует для листинга
	 */
	public function listing()
	{
		if (isset($this->componentSchema['caption'])) {
			$this->setFieldCaption($this->componentSchema['caption']);
		}
		if (isset($this->componentSchema['listing'])) {
			if (isset($this->componentSchema['listing']['align'])) {
				switch ($this->componentSchema['listing']['align']) {
					case "left":
						$this->addAnswerClass('input-left');
						break;
					case "center":
						$this->addAnswerClass('input-center');
						break;
					case "right":
						$this->addAnswerClass('input-center');
						break;
				}
			}
		}

		$data   =   Array();
		foreach (self::$data as $field  => $value) {
			$data[mb_strtoupper($field)] = $value;
		}
		$href   =   '';
		if (isset($this->componentSchema['href'])) {
			$href = strtr($this->componentSchema['href'], $data);
		}
		$data   =   Array(
			'VALUE' =>  $this->fieldValue,
			'HREF' =>  $href,
		);
		$answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}
}