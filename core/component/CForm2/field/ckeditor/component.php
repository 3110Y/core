<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 10:40
 */

namespace core\component\CForm2\field\ckeditor;

use core\component\{
	CForm, fileCache\fileCache, templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\ckeditor
 */
class component extends CForm\AField implements CForm\IField
{
	/**
	 * @const float
	 */
	const VERSION   =   1.0;


    public function init()
    {
        if (isset(self::$data['id'])) {
            $field = $this->componentSchema['field'];
            $id = self::$data['id'];
            $this->addAnswerID("ckeditor-field-{$field}-id-{$id}");
            $this->addAnswerClass('input');
            self::$config['controller']::setCss(self::getTemplate('css/ckeditor.css', __DIR__));
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
        if (isset($this->componentSchema['totalWidth'])) {
            $data['FIELD_STYLE'] = "width: {$this->componentSchema['labelWidth']}; ";
        }
        if (isset($this->componentSchema['labelWidth'])) {
            $data['LABEL_STYLE'] = "width: {$this->componentSchema['labelWidth']}; ";
        }
        if (isset($this->componentSchema['width'])) {
            $data['STYLE'] = "width: {$this->componentSchema['width']}; ";
        }

        if (isset($this->componentSchema['mode'])) {
            $data['MODE'] = $this->componentSchema['mode'];
        } else {
            $data['MODE'] = 'toolbar_default';
        }
        self::$config['controller']::setCss(self::getTemplate('vendor/ckeditor/contents.css', __DIR__));
        self::$config['controller']::setJS(self::getTemplate('vendor/ckeditor/ckeditor.js', __DIR__), true);
        $jsInit =   self::getTemplate('js/init.tpl', __DIR__);
        $data['INIT']             .=     simpleView\component::replace($jsInit, $data);
        fileCache::checkDir('upload');
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
                    $this->addAnswerClass('ckeditor-left');
                    break;
                case 'center':
                    $this->addAnswerClass('ckeditor-center');
                    break;
                case 'right':
                    $this->addAnswerClass('ckeditor-center');
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
        $data['VALUE'] =  self::truncation(trim(strip_tags($this->fieldValue)), 200);
        $data['HREF'] =  $href;
        $answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }

    /**
     * @param string $str
     * @param int $length
     * @return string
     */
    private static function truncation($str, $length)
    {
        $str = substr($str, 0, $length - 2);        //Обрезаем до заданной длины
        $words=explode(' ', $str);                //Разбиваем по словам
        array_splice($words,-1);                //Удаляем последнее слово

        $last=array_pop($words);                //Получаем последнее слово

        for ($i = 1, $iMax = strlen($last); $i < $iMax; $i++) {
            //Ищем и удаляем в конце последнего слова все кроме букв и цифр
            if (preg_match('/\W$/', $last[strlen($last) - 1])) {
                $last = mb_substr($last, 0, strlen($last) - 1);
            } else break;
        }
        $text   =   implode(' ', $words).  ' ' . $last;
        return $text ===  ' '  ?   ''  :   $text. '...';
    }
}