<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 16.12.2017
 * Time: 4:38
 */

namespace application\admin\model;


use \core\component\{
    application\AClass,
    CForm,
    registry\registry
};


/**
 * Class CFormDefault
 * @package application\model
 */
class CFormDefault extends AClass
{
    /**
     * @var array CForm configuration
     */
    public static $config = [];
    /**
     * @var array
     */
    private static $deleteHiddenID = [];
    /**
     * @var array
     */
    private static $editHiddenID = [];

    /**
     * Установка сортировки по умолчанию
     *  $order - ассоциативный массив ['поле' => 'ASC|DESC']
     *  например ['status' => 'ASC'] - сортировка по id статусов по возрастанию
     *
     * @param $order
     */
    public static function setDefaultOrder(array $order) : void
    {
        $orderKey = 'order' . self::$config['controller']::getPageURL() . '/listing';
        if (!isset($_GET['order']) && !isset($_COOKIE[$orderKey])) {
            $_GET['order'] = $order;
        }
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param $controller
     * @param $table
     * @param $caption
     * @param $field
     * @param mixed $condition
     */
    public static function config($controller, $table, $caption, $field, $condition = null): void
    {
        /** @var \core\component\application\AControllers $controller */
        $url = implode('/', $controller::getURL());
        self::$config = Array(
            'controller'    =>  $controller,
            'db'            =>  registry::get('db'),
            'table'         =>  $table,
            'caption'       =>  $caption,
            'field'         =>  $field,
            'mode'          =>  'listing',
            'viewer'        =>  Array(
                'listing' => Array(
                    'where'     =>  $condition ?? [],
                    'type'      =>  'UKListing',
                    'multi'     =>  'UKActionID',
                    'search'    =>  true,
                    'button'    =>  Array(
                        'row'   =>  Array(
                            Array(
                                'action'    => 'edit',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/edit/{ROW_ID}',
                                'title'     => 'Редактировать',
                                'icon'      => 'pencil',
                                'class'     => 'uk-button-primary uk-button-small',
                                'hidden'    =>  self::$editHiddenID
                            ),
                            Array(
                                'action'    => 'delete',
                                'type'      => 'UKButtonSubmitAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/delete/run/{ROW_ID}?redirect=' . $url,
                                'title'     => 'Удалить',
                                'form'      => '#form-listing',
                                'icon'      => 'close',
                                'class'     => 'uk-button-danger  uk-button-small',
                                'hidden'    =>  self::$deleteHiddenID
                            )
                        ),
                        'rows'  =>  Array(
                            Array(
                                'action'    => 'insert',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/insert?' . uniqid('', true) . '&redirect={PAGE_URL}/{PARENT_ID}/edit/',
                                'text'      => 'Добавить',
                                'icon'      => 'plus',
                                'class'     => 'uk-button-primary',
                            ),
                            Array(
                                'action'    => 'delete',
                                'type'      => 'UKButtonSubmitAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/delete/many?redirect=' . $url,
                                'text'      => 'Удалить',
                                'icon'      => 'close',
                                'form'      => '#form-listing',
                                'class'     => 'uk-button-danger',
                            ),
                        ),
                    )
                ),
                'edit' => Array(
                    'type'      => 'UKEdit',
                    'caption'       =>  $caption .': Редактирование',
                    'button'    =>  Array(
                        'rows'  =>  Array(
                            Array(
                                'action'    => 'goBack',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/listing',
                                'text'      => 'Вернуться',
                                'icon'      => 'reply',
                                'class'     => 'uk-button-default',
                            ),
                            Array(
                                'action'    => 'update',
                                'type'      => 'UKButtonAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/update/run/{ROW_ID}',
                                'text'      => 'Сохранить',
                                'icon'      => 'check',
                                'success'   => 'Изменения сохранены',
                                'error'     => 'Изменения не сохранены',
                                'class'     => 'uk-button-primary',
                            )
                        ),
                    ),
                ),
            )
        );
    }

    /**
     * @param string $action
     * @param string $location ''|'row'|'rows'|'edit'
     */
    public static function removeButton (string $action, string $location = '') : void
    {

        switch ($location) {
            case 'row':
                $buttons = &self::$config['viewer']['listing']['button']['row'];
                break;
            case 'rows':
                $buttons = &self::$config['viewer']['listing']['button']['rows'];
                break;
            case 'edit':
                $buttons = &self::$config['viewer']['edit']['button']['rows'];
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case '':
                self::removeButton($action,'row');
                self::removeButton($action,'rows');
                self::removeButton($action,'edit');
            default:
                $buttons = [];
        }

        foreach ($buttons as $key => $button) {
            if (isset($button['action']) && $button['action'] === $action) {
                array_splice($buttons,$key,1);
            }
        }
    }

    /**
     * @param array $button
     * @param string $location ''|'row'|'rows'|'edit'
     */
    public static function addButton (array $button, string $location) : void
    {
        switch ($location) {
            case 'row':
                $buttons = &self::$config['viewer']['listing']['button']['row'];
                break;
            case 'rows':
                $buttons = &self::$config['viewer']['listing']['button']['rows'];
                break;
            case 'edit':
                $buttons = &self::$config['viewer']['edit']['button']['rows'];
                break;
            default:
                $buttons = [];
        }

        $buttons[] = $button;
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param $controller
     * @param mixed $table
     * @param mixed $caption
     * @param mixed $field
     * @param mixed $condition
     * @return array|bool|mixed
     */
    public static function generation($controller, $table = null, $caption = null, $field = null, $condition = null)
    {
        if ([] === self::$config) {
            self::config($controller, $table, $caption, $field, $condition);
        }
        $CForm  =   new CForm\component($controller::$content, 'CONTENT');
        $CForm->setConfig(self::$config);
        $CForm->run();
        return $CForm->getIncomingArray();
    }

    /**
     * @param array $editHiddenID
     */
    public static function setEditHiddenID(array $editHiddenID): void
    {
        self::$editHiddenID = $editHiddenID;
    }

    /**
     * @param array $deleteHiddenID
     */
    public static function setDeleteHiddenID(array $deleteHiddenID): void
    {
        self::$deleteHiddenID = $deleteHiddenID;
    }

    /**
     * Редактирование только одной записи по id (Напрмер настройки)
     *
     * @param int $id
     */
    public static function setOne(int $id = 1): void
    {
        if ([] === self::$config) {
            return;
        }
        self::$config['mode'] = 'edit';
        self::$config['viewer']['edit']['caption'] = str_replace(': Редактирование','',self::$config['viewer']['edit']['caption']);
        self::$config['viewer']['edit']['where'] = [
            'id' => $id
        ];
        self::removeButton('goBack','edit');

    }

    /**
     * Только просмотр списка
     */
    public static function setNoEdit(): void
    {
        if ([] === self::$config) {
            return;
        }
        unset(self::$config['viewer']['listing']['multi']);
        self::removeButton('delete');
        self::removeButton('insert');
        self::removeButton('edit');
    }
}