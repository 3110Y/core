<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:47
 */

namespace core\component\CForm;


/**
 * Class AViewer
 *
 * @package core\component\CForm
 */
abstract class AViewer extends ACForm
{
    /**
     * @var mixed
     */
    protected $answer;

    /**
     * @var array
     */
    protected $button = Array();

    /**
     * @var array
     */
    protected $field  = Array();

    /**
     * @var array
     */
    protected $config = Array();

    /**
     * @var int
     */
    protected $onPage = 10;

    /**
     * @var array
     */
    protected $pagination   =   Array(10,15,25,30,50,75,100);

    /**
     * @var int
     */
    protected $parent       =   0;

    /**
     * @var int
     */
    protected $page         =   1;

    /**
     * @var array
     */
    protected $data         =   Array();

    /**
     * @var int
     */
    protected $totalRows         =   0;

    /**
     * @var int
     */
    protected $totalPage         =   0;




    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Инициализация
     */
    public function init()
    {
        $this->button       =   self::$viewerConfig['button']           ?? $this->button;
        $this->field        =   self::$viewerConfig['field']            ?? $this->field;
        $this->onPage       =   self::$viewerConfig['onPage']           ?? $this->onPage;
        $this->pagination   =   self::$viewerConfig['pagination']       ?? $this->pagination;
        $this->parent       =   parent::$id;
        $this->page         =   self::$viewerConfig['page']             ?? $this->page;
        unset(
            self::$viewerConfig['button'],
            self::$viewerConfig['field'],
            self::$viewerConfig['onPage'],
            self::$viewerConfig['pagination'],
            self::$viewerConfig['parent'],
            self::$viewerConfig['page']
        );
        $this->config       =   self::$viewerConfig;
        $this->pageNow();
        $this->onPage();
        $this->data         =   self::$viewerConfig['data']             ?? $this->fillData();
        foreach ($this->config as $key =>  $value) {
            $this->answer[\mb_strtoupper($key)] =  $value;
        }
    }

    /**
     * Устанавливает текущую страницы
     */
    protected function pageNow(): void
    {
        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->page  = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
    }

    /**
     * Устанавливает на странице всего
     */
    protected function onPage(): void
    {
        $paginationKey   =   'pagination' . self::$controller::getPageURL() . '/' . self::$mode;
        if (isset($_GET['onPage'])) {
            setcookie($paginationKey, $_GET['onPage'], time() + 2592000, '/');
            $this->onPage = (int)$_GET['onPage'];
        } elseif (isset($_COOKIE[$paginationKey])) {
            $this->onPage = (int)$_COOKIE[$paginationKey];
        }

    }

    /**
     * @return array
     */
    protected static function getOrder(): array
    {
        $array = Array();
        $orderKey   =   'order' . self::$controller::getPageURL() . '/' . self::$mode;
        if (isset($_GET['order'])) {
            setcookie($orderKey, serialize($_GET['order']), time() + 2592000, '/');
            $array =  $_GET['order'];
        } elseif (isset($_COOKIE[$orderKey])) {
            $array = unserialize($_COOKIE[$orderKey], []);
        }
        foreach ($array as $key => $value) {
            if ($value === 'NONE') {
                unset($array[$key]);
            }
        }
        return $array;

    }


    protected function fillData()
    {
        $where  = $this->config['where']  ??  Array();
        $where['parent_id'] =   $this->parent;
        $fields =   Array(
            'id'
        );
        foreach ($this->field as $item) {
            if (isset($item['field'])) {
                $fields[] = $item['field'];
            }
        }
        array_unique($fields);
        $order = self::getOrder();
        if (\is_callable($this->config['countFunction'] ?? null)) {
            $this->totalRows = \call_user_func($this->config['countFunction'],self::$table, '1', $where, [], []);
        }
        else {
            $this->totalRows = self::$db->selectCount(self::$table, '1', $where);
        }
        $this->totalPage = (int)ceil ($this->totalRows / $this->onPage);
        if (0 !== $this->totalPage && $this->page >  $this->totalPage) {
            $urlBack = self::$controller::getPageURL() . '/' . self::$id . '/' . parent::$mode;
            self::redirect($urlBack);
        }
        $limit = Array(
            ($this->onPage * $this->page) - $this->onPage,
            $this->onPage
        );
        if (\is_callable($this->config['selectFunction'] ?? null)) {
            return \call_user_func($this->config['selectFunction'],self::$table, $fields, $where, $order, $limit);
        }
        return self::$db->selectRows(self::$table, $fields, $where, $order, $limit);
    }


}