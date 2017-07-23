<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 01.07.17
 * Time: 19:05
 */

namespace core\component\CForm\field\fileUploadMultiple;
use \core\component\{
    CForm,
    fileCache\fileCache,
    templateEngine\engine\simpleView,
    image\component as image
};
use core\core;


/**
 * Class component
 * @package core\component\CForm\field\fileUploadMultiple
 */
class component extends CForm\AField implements CForm\IField
{
    /**
     * @const float
     */
    const VERSION   =   1.3;


    public function init()
    {
        if (isset(self::$data['id'])) {
            $field = $this->componentSchema['table'];
            $id = self::$data['id'];
            $this->addAnswerID("fileUploadMultiple-field-{$field}-id-{$id}");
            $this->addAnswerClass('fileUploadMultiple');
            self::$config['controller']::setCss(self::getTemplate('css/fileUploadMultiple.css', __DIR__));
            self::$config['controller']::setJS(self::getTemplate('vendor/html5sortable/html.sortable.min.js', __DIR__), true);
        }
    }

    public function run()
    {}

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
        if (isset($_GET['sort'], $_POST['sort'])) {
            $sort   =   json_decode($_POST['sort']);
            /** @var \core\component\database\driver\PDO\component $db */
            $db     =   self::$config['db'];
            foreach ($sort as $order_in_img => $id) {
                $value = Array(
                    'order_in_img' => $order_in_img * 100
                );
                $where = Array(
                    'id' => $id
                );
                $db->update($this->componentSchema['table'], $value, $where);
            }
        }
        if (isset($_GET['dell']) && $_GET['dell'] !== '') {
            /** @var \core\component\database\driver\PDO\component $db */
            $db     =   self::$config['db'];
            $where = Array(
                'id' => (int)$_GET['dell'],
            );
            $row    =   $db->selectRow($this->componentSchema['table'], '*', $where);
            if ($row['img'] !== '' && file_exists(core::getDR(true) . $row['img'])) {
                unlink(core::getDR(true) . $row['img']);
            }
            $db->dell($this->componentSchema['table'], $where);
        }
        if (isset($_FILES[$this->componentSchema['unique']], $_FILES[$this->componentSchema['unique']]['tmp_name']) && $_FILES[$this->componentSchema['unique']]['tmp_name'] !== '') {
            $files = $_FILES[$this->componentSchema['unique']];
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
            if ($files['type'] === 'image/gif') {
                $end = '.gif';
            } elseif($files['type'] === 'image/png') {
                $end = '.png';
            } elseif($files['type'] === 'image/jpeg') {
                $end = '.jpeg';
            }
            $thumbnailStore .= '/' . $name . $end;
            $thumbnail->writeImages($_SERVER['DOCUMENT_ROOT']  . $thumbnailStore, true);

            /** @var \core\component\database\driver\PDO\component $db */
            $db     =   self::$config['db'];
            $where = Array(
                'parent_id' => self::$data['id'],
            );
            $row    =   $db->selectRow($this->componentSchema['table'], '`order_in_img`', $where, '`order_in_img` DESC', '0, 1');
            $value = Array(
                'img'           => $thumbnailStore,
                'parent_id'     => self::$data['id'],
                'order_in_img'  => $row['order_in_img'] === false    ?   100 :   $row['order_in_img'] + 100 ,
            );
            $db->inset($this->componentSchema['table'], $value);
            $lastID =   $db->getLastID();
            $row = $db->selectRow($this->componentSchema['table'], '*', Array('id'=>$lastID));
            $option = Array(
                Array(
                    'action'    => 'adapriveResizeMin',
                    'width'     => '200',
                    'height'    => '150'
                ),
                Array(
                    'action'    => 'crop',
                    'width'     => '200',
                    'height'    => '150'
                ),
            );
            $data = Array(
                'IMG_IMG'   => image::image($row['img'], $option),
                'IMG_ID'    => $row['id'],
                'ID'        => $this->componentSchema['table'],
            );
            $jsInit         =   self::getTemplate('tpl/card.tpl', __DIR__);
            $array['content'] =   simpleView\component::replace($jsInit, $data);

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
        foreach ($this->componentSchema as $field  => $value) {
            $data['CS_'. mb_strtoupper($field)] = $value;
        }
        $data['ID']                 =  $this->componentSchema['table'];
        $data['NAME']               =  $this->componentSchema['table'];
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

        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   self::$config['db'];
        $where = Array(
            'parent_id' => self::$data['id'],
        );
        $rows    =   $db->selectRows($this->componentSchema['table'], '*', $where, '`order_in_img` ASC');
        $data['VALUE'] =  Array();
        if ($rows !== false) {
            $option = Array(
                Array(
                    'action'    => 'adapriveResizeMin',
                    'width'     => '200',
                    'height'    => '150'
                ),
                Array(
                    'action'    => 'crop',
                    'width'     => '200',
                    'height'    => '150'
                ),
            );

            foreach ($rows as $row) {
                $data['VALUE'][] =  Array(
                    'IMG_ID'        =>  $row['id'],
                    'IMG_IMG'       =>  image::image($row['img'], $option),
                    'IMG_IMG_BIG'   =>  $row['img'],
                    'IMG_ORDER'     =>  $row['order_in_img']
                );
            }
        }
        $jsInit         =   self::getTemplate('js/init.tpl', __DIR__);
        $data['INIT']   =   simpleView\component::replace($jsInit, $data);
        $answer         =   simpleView\component::replace(self::getTemplate('tpl/edit.tpl', __DIR__), $data);
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
                    $this->addAnswerClass('fileUploadMultiple-left');
                    break;
                case 'center':
                    $this->addAnswerClass('fileUploadMultiple-center');
                    break;
                case 'right':
                    $this->addAnswerClass('fileUploadMultiple-center');
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

        /** @var \core\component\database\driver\PDO\component $db */
        $db     =   self::$config['db'];
        $where = Array(
            'parent_id' => self::$data['id'],
        );
        $count    =   $db->selectCount($this->componentSchema['table'], '*', $where);
        $data['VALUE'] =  "<span class='uk-badge'>{$count}</span>";
        $data['HREF'] =  $href;
        $answer =   simpleView\component::replace(self::getTemplate('tpl/listing.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);
    }
}