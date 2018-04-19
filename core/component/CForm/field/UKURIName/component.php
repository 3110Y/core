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
    simpleView\simpleView
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
        $data['READONLY']       =   $this->readonly     ?   'disabled'  :   '';
        $data['STYLE']          =   $this->style;
        $data['CLASS']          =   $this->class;
        $data['ID']             =   $this->idField;
        $data['HREF']           =   isset($data['HREF'])        ?  "<a href='{$data['HREF']}'"    :  '<span>';
        $data['HREF_TWO']       =   $data['HREF'] == '<span>'   ?    '</span>'                    :   '</a>';
        $data['HREF']           =   simpleView::replace(false, $data, $data['HREF']);
        $this->answer           =   simpleView::replace($this->template, $data);
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
        return false;
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
            // Переводим нижний регистр
            $this->value = mb_strtolower($this->value);
            // Транслитерируем в латиницу
            $rus = array('ё','ж','ц','ч','ш','щ','ю','я','ь','ъ');
            $lat = array('yo','zh','tc','ch','sh','sh','yu','ya','','');
            $this->value = str_replace($rus,$lat,$this->value);
            $from = preg_split('~~u', "абвгдезийклмнопрстуфхыэ", null, PREG_SPLIT_NO_EMPTY);
            $to = preg_split('~~u', "abvgdezijklmnoprstufhie", null, PREG_SPLIT_NO_EMPTY);
            $this->value =  str_replace($from, $to, $this->value);
            // Заменяем всё кроме цифр и латинских букв на `-` (дефис)
            // Используем жадный поиск что бы убрать дублирующиеся `-` (дефисы)
            $this->value = preg_replace('/[^0-9a-z]+/','-', $this->value);
            // Убираем `-` (дефисы) в начале и конце строки
            $this->value = trim($this->value,'-');
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