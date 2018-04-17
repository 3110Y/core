<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 19:12
 */

namespace core\CForm\field\UKImageUpload;

use \core\{
    CForm,
    simpleView\simpleView,
    fileCache\fileCache,
    library as library,
    image\image,
    dir\dir
};


/**
 * Class component
 * @package core\CForm\field\UKImageUpload
 */
class component extends CForm\AField implements CForm\IField
{

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var array
     */
    private $option = Array();

    private $templatePhoto = '';

    public function init()
    {
        parent::init();
        if (isset($this->configField['path'])) {
            $this->path = $this->configField['path'];
            unset($this->configField['path']);
        } else {
            $this->path = $this->idField;
        }
        fileCache::checkDir($this->path);
        $data['PARENT_URL']             =   parent::$id;
        $data['URL']                    =   self::$controller::getPageURL();
        $data['TD']                     =   '';
        $data['GRID']                   =   1;
        $data['PLACEHOLDER']            =   '';
        $data['TABLE']                  =   parent::$table;
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        foreach ($this->row as $key =>  $field) {
            $data['ROW_' . mb_strtoupper($key)] =  $field;
        }
        $data['READONLY']               =   $this->readonly     ?   'display:none;'  :   '';
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] === '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView::replace(false, $data, $data['HREF']);

        $data['IMG']            =   $this->value !== '' &&  $this->value !== null  ?   image::image($this->value, $this->option)   :   $this->value;
        $data['IMG_BIG']        =   $this->value;
        $data['PARAM']          =   json_encode($data);

        //UIkitUpload
        /** @var \core\library\vendor\UIkitUpload\component $UIkitUpload */
        $UIkitUpload    =   library\component::connect('UIkitUpload');
        $UIkitUpload->setCss(self::$controller);
        $UIkitUpload->setJS(self::$controller);
        $data['INIT']           =   $UIkitUpload->returnInit($data);

        $data['VALUE']          =   simpleView::replace(self::getTemplate($this->templatePhoto, __DIR__), $data);
        $this->answer           =   simpleView::replace($this->template, $data);
    }

    public function view()
    {
        $this->option = Array(
            Array(
                'action'    => 'adapriveResizeMax',
                'width'     => '100',
                'height'    => '100'
            ),
        );
        $this->templatePhoto = 'template/photo_view.tpl';
        $this->template         =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function save($id = 0)
    {
        $table = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $fieldName = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $this->answer = Array();
        foreach ($this->configField as $field) {
            if (isset($field['field'])  && $field['field'] == $fieldName) {
                $this->configField = $field;
                break;
            }
        }
        if (isset($this->configField['path'])) {
            $this->path = $this->configField['path'];
            unset($this->configField['path']);
        } else {
            $this->path = $this->idField;
        }
        if (!isset($_FILES[$this->configField['field']])) {
            $this->answer['error'] = "Поле '{$this->configField['field']}' не должно быть пустым";
            return array();
        }
        $where = Array(
            'id' => $id
        );
        $row    =   parent::$db->selectRow($table, $this->configField['field'], $where);
        $valueOld = $row[$this->configField['field']];
        if ($valueOld != '' && file_exists(dir::getDR(true) . $valueOld) && !is_dir(dir::getDR(true) . $this->value)) {
            unlink(dir::getDR(true) .$valueOld);
        }
        $files = $_FILES[$this->configField['field']];
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
        $thumbnailStore = '/filecache/' . $this->path;
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
        $this->value = mb_strtolower(preg_replace('/-{2,}/', '-', strtr(rtrim(trim($name)), $dataText)));
        $name .= "_{$id}_" . uniqid();
        if ($files['type'] === 'image/gif') {
            $end = '.gif';
        } elseif($files['type'] === 'image/png') {
            $end = '.png';
        } elseif($files['type'] === 'image/jpeg') {
            $end = '.jpeg';
        } else {
            $end = '.none';
        }
        fileCache::checkDir($this->path);
        $thumbnailStore .= '/' . $name . $end;
        $thumbnail->writeImages(dir::getDR(true)  . $thumbnailStore, true);
        $option = Array(
            Array(
                'action'    => 'adapriveResizeMin',
                'width'     => '200',
                'height'    => '200'
            ),
            Array(
                'action'    => 'crop',
                'width'     => '200',
                'height'    => '200'
            ),
        );
        $data = Array(
            'ID'   => $this->configField['field'],
            'ROW_ID'    => $id,
            'IMG'        => image::image($thumbnailStore, $option),
            'IMG_BIG'    => $thumbnailStore,
        );
        $value = Array(
            $this->configField['field'] => $thumbnailStore
        );
        parent::$db->update($table, $value, $where);
        $photo              =   self::getTemplate('template/photo.tpl', __DIR__);
        $this->answer['value']     =   $thumbnailStore;
        $this->answer['content']   =   simpleView::replace($photo, $data);
    }

    public function postDelete()
    {
        $where = Array(
            'id'    => $this->row['id']
        );
        $row    =   parent::$db->selectRow(parent::$table, $this->configField['field'], $where );
        $this->delete($row['field']);
        return parent::postDelete();
    }

    public function delete($value)
    {
        if ($value !== '' && file_exists(dir::getDR(true) . $value) && !is_dir(dir::getDR(true) . $value)) {
            unlink(dir::getDR(true) . $value);
        }
    }

    public function edit()
    {
        $this->option = Array(
            Array(
                'action'    => 'adapriveResizeMin',
                'width'     => '200',
                'height'    => '200'
            ),
            Array(
                'action'    => 'crop',
                'width'     => '200',
                'height'    => '200'
            ),
        );
        $this->templatePhoto    =   'template/photo_view.tpl';
        if ($this->value == '') {
            $this->templatePhoto = 'template/photo_no.tpl';
        } else {
            $this->templatePhoto = 'template/photo.tpl';
        }
        $this->template     =   self::getTemplate('template/edit.tpl', __DIR__);

    }

    public function preInsert()
    {
        return $this->required && ($this->value === '' || $this->value === null);
    }

    public function preUpdate()
    {
        return $this->required && ($this->value === '' || $this->value === null);
    }
}