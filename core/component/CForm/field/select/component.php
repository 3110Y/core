<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 1:56
 */

namespace core\component\CForm\field\select;

use \core\component\{
    CForm,
    templateEngine\engine\simpleView
};

/**
 * Class component
 * @package core\component\CForm\field\select
 */
class component extends CForm\AField implements CForm\IField
{
    public function init()
    {
        if (isset(self::$data['id'])) {
            $field = $this->componentSchema['field'];
            $id = self::$data['id'];
            $this->addAnswerID("input-field-{$field}-id-{$id}");
            $this->addAnswerClass('input');
            self::$config['controller']::setCss(self::getTemplate('vendor/select2/dist/css/select2.min.css', __DIR__));
            self::$config['controller']::setJS(self::getTemplate('vendor/select2/dist/js/select2.min.js', __DIR__));
        }
    }

    public function run()
    {

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
            $data['INIT']             .=     simpleView\component::replace($jsInit, Array('ID' => $data['ID']));
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
        if (isset($this->componentSchema['multiple'])) {
            $data['MULTIPLE'] = "multiple='{$this->componentSchema['multiple']}'";
        }
        $data['LIST']    = Array();
        if (
            isset($this->componentSchema['list'])
            && is_array($this->componentSchema['list'])
            && !empty($this->componentSchema['list'])
        ) {
            if (!isset($this->componentSchema['multiple']) || isset($this->componentSchema['def'])) {
                $data['LIST'][] = Array(
                    'LIST_ID'       => 0,
                    'LIST_NAME'     => 'Не выбрано'
                );
            }
            foreach ($this->componentSchema['list'] as $key =>  $option) {
                if(isset($this->componentSchema['listID']) && !empty($this->componentSchema['listID'])) {
                    $id = $option[$this->componentSchema['listID']];
                } elseif (isset($option['id']) && !empty($option['id'])) {
                    $id = $option['id'];
                } else {
                    $id = $key;
                }
                if(isset($this->componentSchema['listName']) && !empty($this->componentSchema['listName'])) {
                    $name = $option[$this->componentSchema['listName']];
                } elseif (isset($option['name']) && !empty($option['name'])) {
                    $name = $option['name'];
                } else {
                    $name = $key;
                }
                $data['LIST'][] = Array(
                    'LIST_ID'       => $id,
                    'LIST_NAME'     =>  $name
                );

            }
        }
        $jsInit =   self::getTemplate('js/init.tpl', __DIR__);
        $data['INIT']             .=     simpleView\component::replace($jsInit, Array('ID' => $data['ID']));


        $answer =   simpleView\component::replace(self::getTemplate('tpl/edit.tpl', __DIR__), $data);
        $this->setComponentAnswer($answer);

    }

    /**
     * генирирует для просмотра
     */
    public function view()
    {

    }
}