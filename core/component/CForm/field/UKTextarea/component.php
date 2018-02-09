<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 0:15
 */

namespace core\component\CForm\field\UKTextarea;

use \core\component\{
    CForm,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\component\CForm\field\UKTextarea
 */
class component extends CForm\AField implements CForm\IField
{


    public function init()
    {
        parent::init();
        $data['TD']                    =   '';
        $data['GRID']          =   1;
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
        $this->value                =   self::truncation(trim(strip_tags($this->value)), 200);
        $this->template             =   self::getTemplate('template/view.tpl', __DIR__);

    }

    public function edit()
    {
        $this->template             =   self::getTemplate('template/edit.tpl', __DIR__);

    }

    public function preInsert()
    {
        return $this->required && $this->value === '';
    }

    public function preUpdate()
    {
        return $this->required && $this->value === '';
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