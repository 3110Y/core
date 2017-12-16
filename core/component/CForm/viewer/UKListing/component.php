<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:38
 */

namespace core\component\CForm\viewer\UKListing;


use \core\component\{
	CForm as CForm,
	templateEngine\engine\simpleView as simpleView
};
use core\core;


/**
 * Class component
 *
 * @package core\component\CForm\viewer\UKListing
 */
class component extends CForm\AViewer implements CForm\IViewer
{
    /**
     * @const float Версия
     */
    const VERSION   =   2.0;


	public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        foreach ($this->pagination as $page) {
            $this->answer['ON_PAGE_LIST'][] =  Array(
                'CLASS' =>  $page == $this->onPage  ?   'uk-active' :   '',
                'URL'   =>  '?onPage=' . $page,
                'TEXT'  =>  $page
            );
        }
        $this->answer['ON_PAGE']            = $this->onPage;
        $this->answer['PARENT']             = $this->parent;
        $this->answer['PAGE']               = $this->page;
        $this->answer['ROW_TO']             = $this->page * $this->onPage;
        $this->answer['ROW_FORM']           = $this->answer['ROW_TO'] - $this->onPage;
        $this->answer['ROW_FORM']           = $this->answer['ROW_FORM'] == 0  ? 1 :   $this->answer['ROW_FORM'];
        $this->answer['TOTAL_ROWS']         = $this->totalRows;
        if ($this->answer['ROW_TO'] > $this->answer['TOTAL_ROWS'] ) {
            $this->answer['ROW_TO']       = $this->answer['TOTAL_ROWS'];
        }

        $this->answer['TOTAL_PAGE']         = $this->totalPage;
        $this->answer['CAPTION']            = parent::$caption;
        $this->answer['PAGINATION']         = $this->getPagination();
    }

    /**
     * Запуск
     */
    public function run()
    {
        if ($this->totalRows == 0) {
            $template = core::getDR(true) . self::getTemplate('template/listNo.tpl', __DIR__);
        } else {
            $template = core::getDR(true) . self::getTemplate('template/list.tpl', __DIR__);
            $orderAll  =   self::getOrder();
            foreach ($this->data as $row) {
                $td = Array(
                    'TH'        => Array(),
                    'TD_FIELD'  => Array(),
                    'TD_BUTTON' => Array(),
                );
                /**
                 * ID
                 */
                if (isset($this->config['multi'])) {
                    $multiName = $this->config['multi'];
                    $multiObject = "core\component\CForm\\field\\{$multiName}\component";
                    if (class_exists($multiObject)) {
                        /** @var \core\component\CForm\field\UKActionID\component $multiComponent */
                        $multiComponent = new $multiObject(Array(), $row);
                        $multiComponent->init();
                        if (!isset($this->answer['TH']['field'])) {
                            $this->answer['TH']['field'] = $multiComponent->getLabel();
                        }
                        $td['TD_FIELD'][] = Array(
                            'COMPONENT' =>  $multiComponent->getAnswer()
                        );
                    }
                }

                /**
                 * Поля
                 */
                foreach ($this->field as $key   =>   $field) {
                    if (!isset($field['field']) && is_string($key)) {
                        $field['field'] = $key;
                    }
                    if (isset($field['field'], $row[$field['field']])) {
                        $field['value'] = $row[$field['field']];
                    }
                    if (!isset($field['mode'])) {
                        $field['mode'] = 'view';
                    }
                    if (isset($field['type'])) {
                        $fieldName = $field['type'];
                        $fieldObject = "core\component\CForm\\field\\{$fieldName}\component";
                        if (class_exists($fieldObject)) {
                            /** @var \core\component\CForm\field\UKInput\component $fieldComponent */
                            $fieldComponent = new $fieldObject($field, $row);
                            $fieldComponent->init();
                            if (!isset($this->answer['TH'][$key])) {
                                $this->answer['TH'][$key] = $fieldComponent->getLabel();
                                if (isset($this->answer['TH'][$key]['FIELD'])) {
                                    $order = $orderAll;
                                    /**
                                     * icon
                                     */
                                    if (!isset($order[$this->answer['TH'][$key]['FIELD']])) {
                                        $this->answer['TH'][$key]['ICON'] = '';
                                    } elseif ($order[$this->answer['TH'][$key]['FIELD']]== 'ASC') {
                                        $this->answer['TH'][$key]['ICON'] = "uk-icon='icon: triangle-up'";
                                    } elseif ($order[$this->answer['TH'][$key]['FIELD']] == 'DESC') {
                                        $this->answer['TH'][$key]['ICON'] = "uk-icon='icon: triangle-down'";
                                    }

                                    /**
                                     * куда
                                     */
                                    if (isset($order[$this->answer['TH'][$key]['FIELD']])) {
                                        if ($order[$this->answer['TH'][$key]['FIELD']] == 'ASC') {
                                            $order[$this->answer['TH'][$key]['FIELD']] = 'DESC';
                                        } else {
                                            $order[$this->answer['TH'][$key]['FIELD']] = 'NONE';
                                        }
                                    } else {
                                        $order[$this->answer['TH'][$key]['FIELD']] = 'ASC';
                                    }
                                    $link = Array();
                                    foreach ($order as $k => $v) {
                                        $link["order[{$k}]"] = $v;
                                    }
                                    $link = http_build_query ($link);
                                    $this->answer['TH'][$key]['HREF'] = "?{$link}";
                                } else {
                                    $this->answer['TH'][$key]['HREF'] = '#';
                                }
                            }
                            $td['TD_FIELD'][] = Array(
                                'COMPONENT' =>  $fieldComponent->getAnswer()
                            );

                        }
                    }
                }

                /**
                 * Кнопки
                 */
                if (isset($this->button['row']) && !empty($this->button['row'])) {
                    foreach ($this->button['row'] as $key => $button) {

                        if (isset($button['type'])) {
                            $buttonName = $button['type'];
                            $buttonObject = "core\component\CForm\\button\\{$buttonName}\component";
                            if (class_exists($buttonObject)) {
                                /** @var \core\component\CForm\button\UKButton\component $fieldComponent */
                                $buttonComponent = new $buttonObject($button, $row);
                                $buttonComponent->init();
                                $buttonComponent->run();
                                if (!isset($this->answer['TH']['button'])) {
                                    $this->answer['TH']['button'] = Array(
                                        'HREF' => '#',
                                        'ICON' => '',
                                        'TEXT' => 'Действия'
                                    );
                                }
                                $td['TD_BUTTON'][] = Array(
                                    'COMPONENT' =>  $buttonComponent->getAnswer()
                                );
                            }
                        }

                    }
                }
                $this->answer['TR'][]   =   $td;
            }

            /**
             * Кнопки
             */
            if (isset($this->button['rows']) && !empty($this->button['rows'])) {
                foreach ($this->button['rows'] as $key => $button) {
                    if (isset($button['type'])) {
                        $buttonName = $button['type'];
                        $buttonObject = "core\component\CForm\\button\\{$buttonName}\component";
                        if (class_exists($buttonObject)) {
                            /** @var \core\component\CForm\button\UKButton\component $fieldComponent */
                            $buttonComponent = new $buttonObject($button, $this->data);
                            $buttonComponent->init();
                            $buttonComponent->run();
                            $this->answer['ROWS'][] = Array(
                                'COMPONENT' =>  $buttonComponent->getAnswer()
                            );
                        }
                    }
                }
            }
        }
        self::$controller::setCss(self::getTemplate('css/list.css', __DIR__));
        $this->answer   =   simpleView\component::replace($template, $this->answer);

    }


    /**
     * Отдает Постраничку
     * @return array данные Постранички
     */
    private function getPagination() :array
    {
        $url = self::$controller::getPageURL() . '/' . self::$id . '/' . parent::$mode . '/';
        $pagination  =   Array();

        if ($this->totalPage === 1) {
            $pagination[] = Array(
                'HREF'  =>  $url . 1,
                'TEXT'  =>  'Вся информация размещена на одной странице',
                'CLASS' =>  'uk-active'
            );
        } elseif ($this->totalPage <= 6) {
            if ($this->page != 1) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page - 1),
                    'TEXT'  =>  '<span uk-pagination-previous></span>',
                );
            }
            for ($i = 1; $i <= $this->totalPage; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            if ($this->page != $this->totalPage) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page + 1),
                    'TEXT'  =>  '<span uk-pagination-next></span>',
                );
            }

        } elseif ($this->totalPage  <= 5) {
            if ($this->page != 1) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . $this->page - 1,
                    'TEXT'  =>  '<span uk-pagination-previous></span>',
                );
            }
            for ($i = 1, $iMax = 7; $i < $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . $this->totalPage,
                'TEXT'  =>  $this->totalPage,
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page + 1),
                'TEXT'  =>  '<span uk-pagination-next></span>',
            );

        } elseif ($this->page >=  ($this->totalPage - 4)) {
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page - 1),
                'TEXT'  =>  '<span uk-pagination-previous></span>',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . 1,
                'TEXT'  =>  1,
            );
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            for ($i = ($this->totalPage - 5), $iMax = $this->totalPage; $i <= $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            if ($this->page != $this->totalPage) {
                $pagination[] = Array(
                    'CLASS' =>  '',
                    'HREF'  =>  $url . ($this->page + 1),
                    'TEXT'  =>  '<span uk-pagination-next></span>',
                );
            }
        } else {
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page - 1),
                'TEXT'  =>  '<span uk-pagination-previous></span>',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . 1,
                'TEXT'  =>  1,
            );
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            for ($i = ($this->page - 2), $iMax = ($this->page + 2); $i < $iMax; $i++) {
                $pagination[] = Array(
                    'HREF'  =>  $url . $i,
                    'TEXT'  =>  $i,
                    'CLASS' =>  $i == $this->page   ?   'uk-active' :   ''
                );
            }
            $pagination[] = Array(
                'CLASS' =>  'uk-disabled',
                'HREF'  =>  '#',
                'TEXT'  =>  '...',
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . $this->totalPage,
                'TEXT'  =>  $this->totalPage,
            );
            $pagination[] = Array(
                'CLASS' =>  '',
                'HREF'  =>  $url . ($this->page + 1),
                'TEXT'  =>  '<span uk-pagination-next></span>',
            );
        }
        return $pagination;
    }

}