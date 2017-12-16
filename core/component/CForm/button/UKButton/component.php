<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 11.12.2017
 * Time: 11:53
 */

namespace core\component\CForm\button\UKButton;


use \core\component\{
    CForm as CForm,
    templateEngine\engine\simpleView
};


/**
 * Class component
 * @package core\component\CForm\button\UKButton
 */
class component extends CForm\AButton implements CForm\IButton
{
    /**
     * @const float Версия
     */
    const VERSION   =   2.0;


    /**
     * Инициализация
     */
    public function init()
    {

    }

    /**
     * Инициализация
     */
    public function run()
    {
        $array = Array();
        foreach ($this->configButton as $key => $value) {
            $array[mb_strtoupper($key)] = $value;
        }
        $array['ID_BUTTON'] = $this->idButton;
        $array['URL_TPL']   = $this->url;
        $array['CLASS']     = $this->class;
        $array['STYLE']     = $this->style;
        $array['PAGE_URL']  = self::$controller::getPageURL();
        $array['PARENT_ID'] = self::$id;
        $array['PARENT_ID'] = self::$id;
        $data = Array();
        foreach ($array as $key => $value) {
            $data['{' . mb_strtoupper($key) . '}'] = $value;
        }
        foreach ($this->row as $key => $value) {
            $data['{ROW_' . mb_strtoupper($key). '}'] = $value;
        }
        $this->url          = strtr($this->url, $data);
        $array['URL']       = $this->url;
        $array['ICON']      = $this->icon;
        $array['TITLE']     = $this->title;
        $array['TOOLTIP']   = $array['TITLE'] != '' ?   'uk-tooltip'    :   '';
        $array['TEXT']      = $this->text;
        $data = Array();
        foreach ($array as $key => $value) {
            $data[mb_strtoupper($key)] = $value;
        }
        foreach ($this->row as $key => $value) {
            $data['ROW_' . mb_strtoupper($key)] = $value;
        }
        $this->template     =   self::getTemplate($this->template, __DIR__);
        $this->answer       =   simpleView\component::replace($this->template, $data);

    }
}