<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 02.03.2018
 * Time: 14:48
 */

namespace core\CForm\field\file;

use \core\{
    CForm,
    simpleView\simpleView,
    fileCache\fileCache,
    library,
    dir\dir
};


class component extends CForm\AField
{
    /**
     * @var string
     */
    private $path = '';

    /**
     *
     */
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

        $data['FILE']           =   $this->value;
        $data['EXTENSION']      =    '.'.pathinfo(strtolower(basename($this->value)), PATHINFO_EXTENSION);
        $data['NAME']           =    preg_replace('/_\d+_[0-9a-f]{13}' . $data['EXTENSION'] .'$/','',pathinfo(strtolower(basename($this->value)), PATHINFO_BASENAME));
        $data['PARAM']          =   json_encode($data);

        //UIkitUpload
        /** @var \core\library\vendor\UIkitUpload\component $UIkitUpload */
        $UIkitUpload    =   library\component::connect('UIkitUpload');
        $UIkitUpload->setCss(self::$controller);
        $UIkitUpload->setJS(self::$controller);
        $data['INIT']           =   $UIkitUpload->returnInit($data);

        $this->answer           =   simpleView::replace($this->template, $data);
    }
    /**
     * просмотр
     */
    public function view()
    {
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
        if ($valueOld != '' && file_exists(dir::getDR(true) . $valueOld) && !is_dir(dir::getDR(true) . $valueOld)) {
            unlink(dir::getDR(true) .$valueOld);
        }
        $files = $_FILES[$this->configField['field']];
        $file = '/filecache/' . $this->path;
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

        $this->value = mb_strtolower(preg_replace('/-{2,}/', '-', strtr(trim($name), $dataText)));
        $name = $this->value . "_{$id}_" . uniqid();
        $extension = '.'.pathinfo(strtolower(basename($files['name'])), PATHINFO_EXTENSION);

        fileCache::checkDir($this->path);
        $file .= '/' . $name . $extension;
        move_uploaded_file($files['tmp_name'],dir::getDR(true)  . $file);
        $data = Array(
            'ID'        => $this->configField['field'],
            'ROW_ID'    => $id,
            'FILE'      => $file,
            'NAME'      => $this->value,
            'EXTENSION' => $extension,
        );
        $value = Array(
            $this->configField['field'] => $file
        );
        parent::$db->update($table, $value, $where);

        if (isset(
            $this->configField['otherTable'],
            $this->configField['otherTableWhere'],
            $this->configField['otherTableValue']
        )) {
            $this->configField['otherTableWhere'][$this->configField['otherTableField']] = $id;
            $query  =   parent::$db->select(
                $this->configField['otherTable'],
                'id',
                $this->configField['otherTableWhere']
            );
            if ($query->rowCount() > 0) {
                $row = $query->fetch();
                $where  =   Array(
                    'id'    =>  $row['id']
                );
                $this->configField['otherTableValue'][$this->configField['field']] = $file;
                $this->configField['otherTableValue'][$this->configField['otherTableField']] = $id;
                parent::$db->update($this->configField['otherTable'], $value, $where);
            } else {
                parent::$db->inset($this->configField['otherTable'], $value);
            }
        }

        $photo              =   self::getTemplate('template/file.tpl', __DIR__);
        $this->answer['value']     =   $file;
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