<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 23:16
 */

namespace core\component\CForm\field\fileUpload;
use core\component\{
	CForm, fileCache\fileCache, templateEngine\engine\simpleView, image\component as image
};
use core\core;


/**
 * Class component
 * @package core\component\CForm\field\fileUpload
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
            $this->addAnswerID("fileUpload-field-{$field}-id-{$id}");
            $this->addAnswerClass('fileUpload');
            self::$config['controller']::setCss(self::getTemplate('css/fileUpload.css', __DIR__));
        }
    }

    public function run()
    {
    }

    public function postDell()
    {
        if ($this->fieldValue !== '' && file_exists(core::getDR(true) . $this->fieldValue)) {
            unlink(core::getDR(true) . $this->fieldValue);
        }
    }

	/**
     * @return array
     */
    public function preUpdate(): array
    {
        $array = Array();
        if (isset($this->componentSchema['required']) && $this->componentSchema['required'] && isset($_FILES[$this->componentSchema['field']])) {
            $name = $this->componentSchema['field'];
            if (isset($this->componentSchema['label']) && $this->componentSchema['label'] != '') {
                $name = $this->componentSchema['label'];
            } elseif (isset($this->componentSchema['caption']) && $this->componentSchema['caption'] != '') {
                $name = $this->componentSchema['caption'];
            } elseif (isset($this->componentSchema['placeholder']) && $this->componentSchema['placeholder'] != '') {
                $name = $this->componentSchema['placeholder'];
            }
            $array['error'] = "Поле \"{$name}\" не должно быть пустым";
        }
        if (isset($_FILES[$this->componentSchema['field']], $_FILES[$this->componentSchema['field']]['tmp_name']) && $_FILES[$this->componentSchema['field']]['tmp_name'] != '') {
        	if (self::$data[$this->componentSchema['field']] != '' && file_exists(core::getDR(true) . self::$data[$this->componentSchema['field']])) {
        		unlink(core::getDR(true) . self::$data[$this->componentSchema['field']]);
	        }
            $files = $_FILES[$this->componentSchema['field']];
            $thumbnail              =   new \Imagick($files['tmp_name']);
            $original_size          =   getimagesize($files['tmp_name']);
            $original_width 		=   $original_size[0];
            $original_height 		=   $original_size[1];
            if ($original_width > 1920 || $original_height > 1080) {
	            $x_ratio = 1920 / $original_width;
	            $y_ratio = 1080 / $original_height;
	            $ratio = min($x_ratio, $y_ratio);
	            $use_x_ratio = ($x_ratio === $ratio);
	            $new_width = $use_x_ratio ? 1920 : floor($original_width * $ratio);
	            $new_height = !$use_x_ratio ? 1080 : floor($original_height * $ratio);
	            $thumbnail->resizeImage($new_width, $new_height, \Imagick::FILTER_LANCZOS, 1);
            }
            $thumbnailStore = '/filecache/' . $this->componentSchema['path'];
            $name = $files['name'];
            $dataText = array(
                ' ' => '-', '\\' => '-', '!' => '', '@' => '-', '#' => '-', '$' => '-',
                '%' => '-', '^' => '-', '&' => '-', '*' => '-', '(' => '-', ')' => '-',
                '+' => '-', '|' => '-', '`' => '-', '~' => '-', '[' => '-', ']' => '-',
                '{' => '-', '}' => '-', ';' => '-', ':' => '-', "'" => '-', '"' => '-', '/' => '-', '—' => '-',
                '<' => '-', '>' => '-', ',' => '-', '?' => '-', '№' => '-', '_' => '-', 'А' => 'A', 'Б' => 'B',
                'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'J', 'З' => 'Z',
                'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch',
                'Ш' => 'Sh', 'Щ' => 'W', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'Je', 'Ю' => 'Yu', 'Я' => 'Ya', 'а' => 'a',
                'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
                'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h',
                'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'je', 'ю' => 'yu', 'я' => 'ya', '--' => '-'
            );
            $name = mb_strtolower(preg_replace('/-{2,}/', '-', strtr(trim($name), $dataText)));
            if (isset(self::$data['id'])) {
                $name .= '_' . self::$data['id'];
            }
            if (isset($_POST['id'])) {
                $name .= '_' . $_POST['id'];
            }
            $name .= '_' . random_int(0, 9);
            if ($files["type"] == 'image/gif') {
                $end = '.gif';
            } elseif($files["type"] == 'image/png') {
                $end = '.png';
            } elseif($files["type"] == 'image/jpeg') {
                $end = '.jpeg';
            }
            $thumbnailStore .= '/' . $name . $end;
            $thumbnail->writeImages($_SERVER['DOCUMENT_ROOT']  . $thumbnailStore, true);
            $array['value'] = $thumbnailStore;
        }
        return $array;
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
	    $option = Array(
		    Array(
			    'action'    => 'adapriveResizeMax',
			    'width'     => '200',
			    'height'    => '200'
		    ),
	    );
        if ( $this->fieldValue != '') {
	        $data['VALUE'] =  '<a href="#modal-center-' . $this->componentSchema['field'] . '-id-' . self::$data['id'] . '" uk-toggle><img src="' . image::image($this->fieldValue, $option) . '" class="fileUpload-dflex" ></a>
								<div id="modal-center-' . $this->componentSchema['field'] . '-id-' . self::$data['id'] . '" uk-modal="center: true">
								    <div class="uk-modal-dialog uk-text-center">
								        <button class="uk-modal-close-outside" type="button" uk-close></button>
								        <img src="'.$this->fieldValue.'" alt="">
								    </div>
								</div>';
        } else {
	        $data['VALUE'] = '<img src="" style="display:none;">';
        }
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
	    fileCache::checkDir($this->componentSchema['path']);

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
                    $this->addAnswerClass('fileUpload-left');
                    break;
                case 'center':
                    $this->addAnswerClass('fileUpload-center');
                    break;
                case 'right':
                    $this->addAnswerClass('fileUpload-center');
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

	    $option = Array(
	    	Array(
	    		'action'    => 'adapriveResizeMax',
			    'width'     => '100',
			    'height'    => '100'
		    ),
	    );
	    if ( $this->fieldValue != '') {
		    $data['VALUE'] =  '<a href="#modal-center-' . $this->componentSchema['field'] . '-id-' . self::$data['id'] . '" uk-toggle><img src="' . image::image($this->fieldValue, $option) . '" class="listing-fileUpload"></a>
								<div id="modal-center-' . $this->componentSchema['field'] . '-id-' . self::$data['id'] . '" uk-modal="center: true">
								    <div class="uk-modal-dialog uk-text-center">
								        <button class="uk-modal-close-outside" type="button" uk-close></button>
								        <img src="'.$this->fieldValue.'" alt="">
								    </div>
								</div>';
	    } else {
		    $data['VALUE'] = '<span class="uk-label uk-label-danger">Изображение не загружено</span>';
	    }
        $data['HREF'] =  $href;
        $answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }
}