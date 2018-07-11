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
    application\AControllers,
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
     * @var AControllers
     */
    public static $controller;

    /**
     * Deprecated! Use rowButtonController!
     *
     * @var array
     */
    private static $deleteHiddenID = [];

    /**
     * Deprecated! Use rowButtonController!
     *
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
        /* Простите, но более элегантного решения я не придумал =(*/
        #TODO сделать по человечески
        if (!isset($_GET['order']) && !isset($_COOKIE[$orderKey])) {
            $_GET['order'] = $order;
        }
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param AControllers|null $controller
     * @param $table
     * @param $caption
     * @param $field
     * @param mixed $condition
     */
    public static function config(?AControllers $controller, $table, $caption, $field, $condition = null): void
    {
        /** @var \core\component\application\AControllers $controller */
        if (null !== $controller) {
            self::$controller = $controller;
        }
        $url = implode('/', self::$controller::getURL());
        self::$config = array_merge_recursive(self::$config,[
            'controller'    =>  self::$controller,
            'db'            =>  registry::get('db'),
            'table'         =>  $table,
            'caption'       =>  $caption,
            'field'         =>  $field,
            'mode'          =>  'listing',
            'viewer'        =>  [
                'listing' => [
                    'where'     =>  $condition ?? [],
                    'type'      =>  'UKListing',
                    'multi'     =>  'UKActionID',
                    'search'    =>  true,
                    'button'    =>  [
                        'row'   =>  [
                            [
                                'action'    => 'edit',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/edit/{ROW_ID}',
                                'title'     => 'Редактировать',
                                'icon'      => 'pencil',
                                'class'     => 'uk-button-primary uk-button-small',
                                'hidden'    =>  self::$editHiddenID
                            ],
                            [
                                'action'    => 'delete',
                                'type'      => 'UKButtonSubmitAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/delete/run/{ROW_ID}?redirect=' . $url,
                                'title'     => 'Удалить',
                                'form'      => '#form-listing',
                                'icon'      => 'close',
                                'class'     => 'uk-button-danger  uk-button-small',
                                'hidden'    =>  self::$deleteHiddenID
                            ]
                        ],
                        'rows'  =>  [
                            [
                                'action'    => 'insert',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/insert?' . uniqid('', true) . '&redirect={PAGE_URL}/{PARENT_ID}/edit/',
                                'text'      => 'Добавить',
                                'icon'      => 'plus',
                                'class'     => 'uk-button-primary',
                            ],
                            [
                                'action'    => 'delete',
                                'type'      => 'UKButtonSubmitAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/delete/many?redirect=' . $url,
                                'text'      => 'Удалить',
                                'icon'      => 'close',
                                'form'      => '#form-listing',
                                'class'     => 'uk-button-danger',
                            ],
                        ],
                    ]
                ],
                'edit' => [
                    'type'      => 'UKEdit',
                    'caption'       =>  $caption .': Редактирование',
                    'button'    =>  [
                        'rows'  =>  [
                            [
                                'action'    => 'goBack',
                                'type'      => 'UKButton',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/listing',
                                'text'      => 'Вернуться',
                                'icon'      => 'reply',
                                'class'     => 'uk-button-default',
                            ],
                            [
                                'action'    => 'update',
                                'type'      => 'UKButtonAjax',
                                'url'       => '{PAGE_URL}/{PARENT_ID}/api/action/update/run/{ROW_ID}',
                                'text'      => 'Сохранить',
                                'icon'      => 'check',
                                'success'   => 'Изменения сохранены',
                                'error'     => 'Изменения не сохранены',
                                'class'     => 'uk-button-primary',
                            ]
                        ],
                    ],
                ],
            ]
        ]);
    }

    /**
     * @param string $action
     * @param string $location ''|'row'|'rows'|'edit'
     * @throws \BadMethodCallException
     */
    public static function removeButton (string $action, string $location = '') : void
    {
        if ([] === self::$config) {
            throw new \BadMethodCallException('Конфиги CFromDefault не заданы');
        }

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
     * @throws \BadMethodCallException
     */
    public static function addButton (array $button, string $location) : void
    {
        if ([] === self::$config) {
            throw new \BadMethodCallException('Конфиги CFromDefault не заданы');
        }
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
     * @param AControllers|null $controller
     * @param mixed $table
     * @param mixed $caption
     * @param mixed $field
     * @param mixed $condition
     * @return array|bool|mixed
     */
    public static function generation(?AControllers $controller = null, $table = null, $caption = null, $field = null, $condition = null)
    {
        /** @var \core\component\application\AControllers $controller */
        if (null !== $controller) {
            self::$controller = $controller;
        }
        if ([] === self::$config) {
            self::config(null, $table, $caption, $field, $condition);
        }
        $controller = self::$controller;
        $CForm  =   new CForm\component($controller::$content, 'CONTENT');
        $CForm->setConfig(self::$config);
        $CForm->run();
        return $CForm->getIncomingArray();
    }

    /**
     * Deprecated! Use rowButtonController!
     *
     * @param array $editHiddenID
     */
    public static function setEditHiddenID(array $editHiddenID): void
    {
        self::$editHiddenID = $editHiddenID;
    }

    /**
     * Deprecated! Use rowButtonController!
     *
     * @param array $deleteHiddenID
     */
    public static function setDeleteHiddenID(array $deleteHiddenID): void
    {
        self::$deleteHiddenID = $deleteHiddenID;
    }

    /**
     * Установка контроллера кнопок.
     * Параметр - коллбэк, вызывается перед выводом, результат выводит вместо кнопки
     *  function(CForm\IButton $button): CForm\IButton
     *
     * @param callable $buttonController
     */
    public static function setRowButtonController(callable $buttonController): void
    {
        if (!isset(self::$config['viewer'])) {
            self::$config['viewer'] = [];
        }
        if (!isset(self::$config['viewer']['listing'])) {
            self::$config['viewer']['listing'] = [];
        }
        self::$config['viewer']['listing']['rowController'] = $buttonController;
    }

    /**
     * Редактирование только одной записи по id (Напрмер настройки)
     *
     * @param int $id
     * @throws \BadMethodCallException
     */
    public static function setOne(int $id = 1): void
    {
        if ([] === self::$config) {
            throw new \BadMethodCallException('Конфиги CFromDefault не заданы');
        }
        self::$config['mode'] = 'edit';
        self::$config['viewer']['edit']['caption'] = str_replace(': Редактирование','',self::$config['viewer']['edit']['caption']);
        self::$config['viewer']['edit']['page'] = $id;
        self::removeButton('goBack','edit');

    }

    /**
     * Функция выборки
     *
     * Вызов
     *  - $listFunction($table, $fields, $where, $order, $limit): array // результат и параметры аналогичны PDO\selectRows
     *  - ($countFunction ?? $listFunction)($table, '1', $where): int // результат и параметры аналогичны PDO\selectCount
     *
     * @param callable $listFunction
     * @param callable|null $countFunction
     */
    public static function setDataFunctions(callable $listFunction, ?callable $countFunction = null): void
    {
        if (!isset(self::$config['viewer'])) {
            self::$config['viewer'] = [];
        }
        if (!isset(self::$config['viewer']['listing'])) {
            self::$config['viewer']['listing'] = [];
        }

        self::$config['viewer']['listing']['selectFunction'] = $listFunction;
        self::$config['viewer']['listing']['countFunction'] = $countFunction ?? $listFunction;

    }

    /**
     * Только просмотр списка
     * @throws \BadMethodCallException
     */
    public static function setNoEdit(): void
    {
        if ([] === self::$config) {
            throw new \BadMethodCallException('Конфиги CFromDefault не заданы');
        }
        unset(self::$config['viewer']['listing']['multi']);
        self::removeButton('delete');
        self::removeButton('insert');
        self::removeButton('edit');
    }
}