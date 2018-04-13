<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 11.12.2017
 * Time: 11:53
 */

namespace core\component\CForm\button\UKButton;


use \core\component\{
    CForm,
    simpleView\simpleView
};


/**
 * Class component
 * @package core\component\CForm\button\UKButton
 */
class component extends CForm\AButton implements CForm\IButton
{


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

        $dataAttr = '';
        if (isset($array['DATA']) && \is_array($array['DATA'])) {
            foreach ($array['DATA'] as $key => $value) {
                $dataAttr .= " data-{$key}='{$value}' ";

            }
            $array['DATA'] = $dataAttr;
        } else {
            $array['DATA'] = '';
        }

        $data = Array();
        foreach ($array as $key => $value) {
            $data[mb_strtoupper($key)] = $value;
        }
        foreach ($this->row as $key => $value) {
            $data['ROW_' . mb_strtoupper($key)] = $value;
        }
        $this->template     =   self::getTemplate($this->template, __DIR__);

        $isHide = false;
        if (isset($this->row['id'], $this->configButton['hidden'])) {
            if (!\is_array($this->configButton['hidden'])) {
                $this->configButton['hidden'] = [
                    $this->configButton['hidden']
                ];
            }
            $this->configButton['hidden']   = array_flip($this->configButton['hidden']);
            if (isset($this->configButton['hidden'][$this->row['id']])) {
                $isHide   =   true;
            }
        }
        if (!$isHide) {
            $this->answer = simpleView::replace($this->template, $data);
        }

    }
}