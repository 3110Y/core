<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 19:12
 */

namespace core\component\CForm\field\UKGallaryUploadMultiple;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView,
    fileCache\fileCache,
    library as library,
    image\component as image
};
use core\core;


/**
 * Class component
 * @package core\component\CForm\field\UKGallaryUploadMultiple
 */
class component extends CForm\AField implements CForm\IField
{
    /**
     * @const float Версия
     */
    const VERSION   =   2.0;

    /**
     * @var string
     */
    private $path = '';

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
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        foreach ($this->row as $key =>  $field) {
            $data['ROW_' . mb_strtoupper($key)] =  $field;
        }
        $data['TABLE']          =   parent::$table;
        $data['TABLE_LINK']     =   $this->configField['table']['link'];
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView\component::replace(false, $data, $data['HREF']);

        $data['INIT']           =   '';

        //html5sortable
        /** @var \core\component\library\vendor\html5sortable\component $html5sortable */
        $UIkitUpload    =   library\component::connect('html5sortable');
        $UIkitUpload->setCss(self::$controller);
        $UIkitUpload->setJS(self::$controller);
        $data['INIT']           .=   $UIkitUpload->returnInit($data);

        //UIkitUpload
        /** @var \core\component\library\vendor\UIkitUpload\component $UIkitUpload */
        $UIkitUpload    =   library\component::connect('UIkitUpload');
        $UIkitUpload->setCss(self::$controller);
        $UIkitUpload->setJS(self::$controller);
        $data['INIT']           .=   $UIkitUpload->returnInit($data, 'initMultiple.tpl');
        $data['INIT']           .=   simpleView\component::replace(self::getTemplate('js/init.tpl', __DIR__), $data);


        $where = Array(
            'parent_id' => $this->row['id'],
        );
        $rows    =   parent::$db->selectRows($this->configField['table']['link'], '*', $where, '`order_in_img` ASC');
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
        $this->answer           =   simpleView\component::replace(self::getTemplate($this->template, __DIR__), $data);
    }

    public function view()
    {
        $this->template = 'template/view.tpl';

    }

    public function sort($id = 0)
    {
        $this->answer = Array();
        $table = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $fieldName = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $tableLink = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $this->answer = Array();
        foreach ($this->configField as $field) {
            if (isset($field['table']['link']) && $tableLink == $field['table']['link']) {
                $this->configField = $field;
                break;
            }
        }
        $sort   =   json_decode($_POST['sort']);
        foreach ($sort as $order_in_img => $id) {
            $value = Array(
                'order_in_img' => $order_in_img * 100
            );
            $where = Array(
                'id' => $id
            );
            parent::$db->update($this->configField['table']['link'], $value, $where);
        }
    }

    public function save($id = 0)
    {
        $this->answer = Array();
        $table = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $fieldName = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $tableLink = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $this->answer = Array();
        foreach ($this->configField as $field) {
            if (isset($field['table']['link']) && $tableLink == $field['table']['link']) {
                $this->configField = $field;
                break;
            }
        }

        if (isset($this->configField['path'])) {
            $this->path = $this->configField['path'];
            unset($this->configField['path']);
        } elseif (!isset($this->configField['path']) && isset($field['table']['link'])) {
            $this->path = $tableLink;
            unset($this->configField['path']);
        } else {
            $this->path = $this->idField;
        }
/*        var_dump($table);
        var_dump($fieldName);
        var_dump($tableLink);
        var_dump($this->configField );
        die();*/
        $files = $_FILES[$tableLink];
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
        // chmod(core::getDR(true)  . $thumbnailStore, 0777);
        $thumbnailStore .= '/' . $name . $end;
        $thumbnail->writeImages(core::getDR(true)  . $thumbnailStore, true);

        $where = Array(
            'parent_id' => $id,
        );
        $row    =   parent::$db->selectRow($tableLink, '`order_in_img`', $where, '`order_in_img` DESC', '0, 1');

        $value = Array(
            'img'           => $thumbnailStore,
            'parent_id'     => $id,
            'order_in_img'  => $row['order_in_img'] === false    ?   100 :   $row['order_in_img'] + 100 ,
        );
        parent::$db->inset($tableLink, $value);
        $lastID =   parent::$db->getLastID();
        $row = parent::$db->selectRow($tableLink, '*', Array('id'=>$lastID));
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
            'IMG_IMG'   => image::image($thumbnailStore, $option),
            'IMG_ID'    => $row['id'],
            'ID'        => $tableLink,
        );
        $photo         =   self::getTemplate('template/card.tpl', __DIR__);
        $this->answer['content'] =   simpleView\component::replace($photo, $data);
    }

    public function postDelete()
    {
        $where = Array(
            'parent_id' => $this->row['id'],
        );
        $rows    =   parent::$db->selectRows($this->configField['table']['link'], '*', $where, '`order_in_img` ASC');
        foreach ($rows as $row) {
            $this->delete($row['img']);
        }
        parent::$db->dell($this->configField['table']['link'], $where);
        return false;
    }

    public function delete($value)
    {
        if ($value !== '' && file_exists(core::getDR(true) . $value)) {
            unlink(core::getDR(true) . $value);
        }
    }

    public function dell($id = 0)
    {
        $this->answer = Array();
        $table = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $fieldName = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $tableLink = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $dellID = parent::$subURL[parent::$subURLNow];
        parent::$subURLNow++;
        $this->answer = Array();
        foreach ($this->configField as $field) {
            if (isset($field['table']['link']) && $tableLink == $field['table']['link']) {
                $this->configField = $field;
                break;
            }
        }
        $where = Array(
            'id' => $dellID,
        );
        $row   =   parent::$db->selectRow($this->configField['table']['link'], '*', $where, '`order_in_img` ASC');
        $this->delete($row['img']);
        parent::$db->dell($this->configField['table']['link'], $where);
    }

    public function edit()
    {
        $this->template = 'template/edit.tpl';
    }

    public function preInsert()
    {
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        return $this->required && $this->value === '';
    }
}