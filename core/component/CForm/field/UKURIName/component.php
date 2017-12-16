<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 15.12.2017
 * Time: 18:28
 */

namespace core\component\CForm\field\UKURIName;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\UKURIName
 */
class component extends CForm\AField implements CForm\IField
{


    public function init()
    {
        parent::init();
        $data['TD']                    =   '';
        $data['GRID']                 =   '1-1';
        $data['PLACEHOLDER']          =   '';
        foreach ($this->configField as $key =>  $field) {
            $data[mb_strtoupper($key)] =  $field;
        }
        $data['VALUE']          =   $this->value;
        $data['MODE_FIELD']     =   $this->modeField;
        $data['LABEL']          =   $this->labelField['TEXT'];
        $data['REQUIRED']       =   $this->required     ?   '*'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView\component::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView\component::replace($this->template, $data);
    }

    public function view()
    {
        $this->template     =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function edit()
    {
        $this->template     =   self::getTemplate('template/edit.tpl', __DIR__);
    }

    public function preInsert()
    {
        $this->generationValue();
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        $this->generationValue();
        return $this->required && $this->value === '';
    }

    /**
     * Генерирует значение
     * @return bool
     */
    public function generationValue()
    {
        if ($this->value === '' && isset($this->row[$this->configField['attached']])) {
            $this->value = $this->row[$this->configField['attached']];
        }
        if ($this->value === '') {
            $this->value = uniqid();
        }
        if ($this->value !== '/') {
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
            $this->value = mb_strtolower(preg_replace('/-{2,}/', '-', strtr(rtrim(trim($this->value)), $dataText)));
        }
        return false;
    }

    /**
     * Проверка на уникальность
     * @param string $url URL
     * @param int $i доролнительный ID
     * @return string
     */
    private function checkUniqid($url, $i=2): string
    {
        $where  =   Array(
            $this->configField['field'] =>  $url
        );
        $count  =   parent::$db->selectCount(parent::$table, $this->configField['field'], $where);
        if ($count > 0) {
            $url2    =  $url . '_' . $i;
            $where  =   Array(
                $this->configField['field'] =>  $url2
            );
            $count  =   parent::$db->selectCount(parent::$table, $this->configField['field'], $where);
            if ($count > 0) {
                $url = $this->checkUniqid($url, ++$i);
            }
        }
        return $url;
    }


}