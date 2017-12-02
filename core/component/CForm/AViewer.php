<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 9.6.2017
 * Time: 17:47
 */

namespace core\component\CForm;
use core\core;
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
        $this->button       =   isset(self::$viewerConfig['button'])            ?? self::$viewerConfig['button'];
        $this->field        =   isset(self::$viewerConfig['field'])             ?? self::$viewerConfig['field'];
        $this->onPage       =   isset(self::$viewerConfig['onPage'])            ?? self::$viewerConfig['onPage'];
        $this->pagination   =   isset(self::$viewerConfig['pagination'])        ?? self::$viewerConfig['pagination'];
        $this->parent       =   parent::$id;
        $this->page         =   isset(self::$viewerConfig['page'])              ?? self::$viewerConfig['page'];
        unset(
            self::$viewerConfig['button'],
            self::$viewerConfig['field'],
            self::$viewerConfig['onPage'],
            self::$viewerConfig['pagination'],
            self::$viewerConfig['parent'],
            self::$viewerConfig['page']
        );
        $this->config   =   self::$viewerConfig;
        $this->pageNow();
        $this->onPage();
    }

    /**
     * Устанавливает текущую страницы
     */
    private function pageNow()
    {
        if (isset(parent::$subURL[parent::$subURLNow])) {
            $this->page  = parent::$subURL[parent::$subURLNow];
            parent::$subURLNow++;
        }
    }

    /**
     * Устанавливает на странице всего
     */
    private function onPage()
    {
        $paginationKey   =   'pagination' . self::$controller::getPageURL() . '/' . self::$mode;
        if (isset($_GET['onPage'])) {
            setcookie($paginationKey, $_GET['onPage'], time() + 2592000, '/');
            $this->onPage = (int)$_GET['onPage'];
        } elseif (isset($_COOKIE[$paginationKey])) {
            $this->onPage = (int)$_COOKIE[$paginationKey];
        }

    }
}