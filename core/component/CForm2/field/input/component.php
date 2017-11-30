<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 18.5.2017
 * Time: 13:37
 */

namespace core\component\CForm2\field\input;

use \core\component\{
	CForm2,
	templateEngine\engine\simpleView
};


/**
 * Class component
 *
 * @package core\component\CForm\field\input
 */
class component extends CForm2\AField implements CForm2\IField
{
	/**
	 * @const float Версия
	 */
	const VERSION   =   1.0;


	public function init()
	{
	    if (isset(self::$data['id'])) {
            $field = $this->componentSchema['field'];
            $id = self::$data['id'];
            $this->addAnswerID("input-field-{$field}-id-{$id}");
            $this->addAnswerClass('input');
		    self::$config['controller']::setCss(self::getTemplate('css/input.css', __DIR__));
        }
	}

	public function run()
	{

	}

    /**
     * @return array
     */
    public function preUpdate(): array
	{
		if (isset($this->componentSchema['required']) && $this->componentSchema['required'] && trim($this->fieldValue) == '') {
			$name = $this->componentSchema['field'];
			if (isset($this->componentSchema['label']) && $this->componentSchema['label'] != '') {
				$name = $this->componentSchema['label'];
			} elseif (isset($this->componentSchema['caption']) && $this->componentSchema['caption'] != '') {
				$name = $this->componentSchema['caption'];
			} elseif (isset($this->componentSchema['placeholder']) && $this->componentSchema['placeholder'] != '') {
				$name = $this->componentSchema['placeholder'];
			}
			return Array(
				'error' => "Поле \"{$name}\" не должно быть пустым",
			);
		}
        return Array();
	}

	/**
	 * генирирует для редактирования
	 */
	public function edit()
	{
		$data   =   Array(
			'PREV_ICON'         =>  '',
			'POST_ICON'         =>  '',
			'REQUIRED'          =>  '',
			'PLACEHOLDER'       =>  '',
			'LABEL'             =>  '',
			'LABEL_TITLE'       =>  '',
			'LABEL_CLASS'       =>  '',
			'LABEL_STYLE'       =>  '',
			'STYLE'             =>  '',
			'CLASS'             =>  '',
			'FIELD_CLASS'       =>  '',
			'FIELD_STYLE'       =>  '',
			'CONTROLS_CLASS'    =>  '',
			'TOOLTIP'           =>  '',
			'CONTROLS_STYLE'    =>  '',
			'TOP_PLACEHOLDER'   =>  '',
			'INIT'              =>  '',
		);
		foreach (self::$data as $field  => $value) {
			$data['DATA_'. mb_strtoupper($field)] = $value;
		}
		$data['VALUE']              =  $this->fieldValue;
		$data['ID']                 =  $this->componentSchema['field'];
		$data['NAME']               =  $this->componentSchema['field'];
		if (isset($this->componentSchema['prevIcon'])) {
			$data['PREV_ICON'] = "<span class='uk-form-icon' uk-icon='icon: {$this->componentSchema['prevIcon']}'></span>";
		}
		if (isset($this->componentSchema['postIcon'])) {
			$data['POST_ICON'] = "<span class='uk-form-icon uk-form-icon-flip' uk-icon='icon: {$this->componentSchema['postIcon']}'></span>";
		}
		if (isset($this->componentSchema['required']) && $this->componentSchema['required']) {
			$data['REQUIRED']  =   'required';
		}
		if (isset($this->componentSchema['placeholder'])) {
			$data['PLACEHOLDER']  =   $this->componentSchema['placeholder'];
		}
		if (isset($this->componentSchema['labelClass'])) {
			$data['LABEL_CLASS']  =   $this->componentSchema['labelClass'];
		}
		if (isset($this->componentSchema['labelStyle'])) {
			$data['LABEL_STYLE']  =   $this->componentSchema['labelStyle'];
		}
		if (isset($this->componentSchema['Style'])) {
			$data['STYLE']  =   $this->componentSchema['Style'];
		}
		if (isset($this->componentSchema['class'])) {
			$data['CLASS']  =   $this->componentSchema['class'];
		}
		if (isset($this->componentSchema['fieldClass'])) {
			$data['FIELD_CLASS']  =   $this->componentSchema['fieldClass'];
		}
		if (isset($this->componentSchema['fieldStyle'])) {
			$data['FIELD_STYLE']  =   $this->componentSchema['fieldStyle'];
		}
		if (isset($this->componentSchema['controlsClass'])) {
			$data['CONTROLS_CLASS']  =   $this->componentSchema['controlsClass'];
		}
		if (isset($this->componentSchema['tooltip']) && $this->componentSchema['tooltip']) {
			$data['TOOLTIP']  =   'uk-tooltip';
		}
		if (isset($this->componentSchema['controlsStyle'])) {
			$data['CONTROLS_STYLE']  =   $this->componentSchema['controlsStyle'];
		}


		if (isset($this->componentSchema['label'])) {
			$data['LABEL']          =   $this->componentSchema['label'];
			$data['LABEL_TITLE']    =   $this->componentSchema['label'];
		} else {
			$data['LABEL_CLASS']    .= 'display-none';
		}
		if (isset($this->componentSchema['labelTitle'])) {
			$data['LABEL_TITLE']  =   $this->componentSchema['labelTitle'];
		}
		if (isset($this->componentSchema['topPlaceholder'])) {
			$data['TOP_PLACEHOLDER']    =     $this->componentSchema['topPlaceholder'];
			$data['PLACEHOLDER']        =     '';
			$jsInit =   self::getTemplate('vendor/label_better-master/init.tpl', __DIR__);
			$data['INIT']             =     simpleView\component::replace($jsInit, Array('ID' => $data['ID']));
			self::$config['controller']::setJs(self::getTemplate('vendor/label_better-master/jquery.label_better.min.js', __DIR__));
		}
		if (isset($this->componentSchema['totalWidth'])) {
			$data['FIELD_STYLE'] = "width: {$this->componentSchema['labelWidth']}; ";
		}
		if (isset($this->componentSchema['labelWidth'])) {
			$data['LABEL_STYLE'] = "width: {$this->componentSchema['labelWidth']}; ";
		}
		if (isset($this->componentSchema['width'])) {
			$data['STYLE'] = "width: {$this->componentSchema['width']}; ";
		}


		$answer =   simpleView\component::replace(self::getTemplate('tpl/edit.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}


	/**
	 * генирирует для просмотра
	 */
	public function view()
	{
		if (isset($this->componentSchema['caption'])) {
			$this->setFieldCaption($this->componentSchema['caption']);
		}
		if (isset($this->componentSchema[self::$mode], $this->componentSchema[self::$mode]['align'])) {
            switch ($this->componentSchema[self::$mode]['align']) {
                case 'left':
                    $this->addAnswerClass('input-left');
                    break;
                case 'center':
                    $this->addAnswerClass('input-center');
                    break;
                case 'right':
                    $this->addAnswerClass('input-center');
                    break;
            }
		}

		$data   =   Array();
		foreach (self::$data as $field  => $value) {
			$data['DATA_' . mb_strtoupper($field)] = $value;
		}
		$href   =   '';
		if (isset($this->componentSchema['href'])) {
			$href = strtr($this->componentSchema['href'], $data);
		}
		$data['VALUE'] =  $this->fieldValue;
		$data['HREF'] =  $href;
		$answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
		$this->setComponentAnswer($answer);
	}
}