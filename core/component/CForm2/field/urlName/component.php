<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 13.06.17
 * Time: 9:06
 */

namespace core\component\CForm\field\urlName;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\urlName
 */
class component extends CForm\AField implements CForm\IField
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
            $this->addAnswerID("urlName-field-{$field}-id-{$id}");
            $this->addAnswerClass('urlName');
            self::$config['controller']::setCss(self::getTemplate('css/urlName.css', __DIR__));
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
        $array = Array();
        if (isset($this->componentSchema['required']) && $this->componentSchema['required'] && trim($this->fieldValue) == '') {
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

        if ($this->fieldValue === '') {
            if (
                isset($this->componentSchema['attached'], $_POST[$this->componentSchema['attached']])
                && $_POST[$this->componentSchema['attached']] != ''
            ) {
                $array['value'] = $_POST[$this->componentSchema['attached']];
            } elseif (isset($_POST['id']) && $_POST['id'] != '') {
                $array['value'] = $_POST['id'];
            } else {
                $array['value']  = date('Y-m-d-H:i:s');
            }
        } else {
            $array['value'] = $this->fieldValue;
        }
        if ($array['value'] !== '/') {
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
            $array['value'] = mb_strtolower(preg_replace('/-{2,}/', '-', strtr(trim($array['value']), $dataText)));
            $array['value'] = $this->checkUniqid($array['value']);
        }
        return $array;
    }

    /**
     * Проверка на уникальность
     * @param string $url URL
     * @param int $i доролнительный ID
     * @return string
     */
    private function checkUniqid($url, $i=2): string
    {
        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   self::$config['controller']::get('db');
        $where  =   Array(
            $this->componentSchema['field'] =>  $url
        );
        $count  =   $db->selectCount(self::$config['table'], $this->componentSchema['field'], $where);
        if ($count > 0) {
            $url2    =  $url . '_' . $i;
            $where  =   Array(
                $this->componentSchema['field'] =>  $url2
            );
            $count  =   $db->selectCount(self::$config['table'], $this->componentSchema['field'], $where);
            if ($count > 0) {
                $url = $this->checkUniqid($url, ++$i);
            }
        }
        return $url;
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
                    $this->addAnswerClass('urlName-left');
                    break;
                case 'center':
                    $this->addAnswerClass('urlName-center');
                    break;
                case 'right':
                    $this->addAnswerClass('urlName-center');
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