<?php
/**
 * Created by PhpStorm.
 * User: gaevoy
 * Date: 12.06.17
 * Time: 19:32
 */

namespace core\component\rules;


/**
 * Class component
 * @package core\component\rules
 */
class component
{
    /**
     * @var string URL страницы авторизации
     */
    private $authorizationURL = '';
    /**
     * @var string URL 404 страницы
     */
    private $authorizationNoPage = '';
    /**
     * @var string URL пустой страницы
     */
    private $authorizationBlank = '';
    /**
     * @var string ключ
     */
    private $key = '';
    /**
     * @var \core\components\PDO\component ДБ
     */
    private static $db;
    /**
     * @var string URL текущей страницы
     */
    private $URL;
    /**
     * @var array Данные обьекта
     */
    private $row = Array();


    /**
     * component constructor.
     * @param string $URL текущий url
     */
    public function __construct(string $URL)
    {
        $this->URL      =   $URL;
    }

    public function check()
    {
        $where = Array(
            'name' => $this->key,
        );

        $this->row = self::$db->selectRow('core_rules_objects', '*', $where);
        if ($this->row === false) {
            $value = Array(
                'name'              => $this->key,
                'date_insert'       => date('Y-m-d H:i:s')
            );
            self::$db->inset('core_rules_objects', $value);
            $this->row = self::$db->selectRow('core_rules_objects', '*', $where);
        }
    }

    /**
     * Устанавливает  URL страницы авторизации
     * @param string $authorizationURL URL страницы авторизации
     * @return $this
     */
    public function setAuthorizationURL(string $authorizationURL)
    {
        $this->authorizationURL =   $authorizationURL;
        return $this;
    }

    /**
     * Устанавливает URL 404 страницы
     * @param string $authorizationNoPage URL 404 страницы
     * @return $this
     */
    public function setAuthorizationNoPage(string $authorizationNoPage)
    {
        $this->authorizationNoPage  =   $authorizationNoPage;
        return $this;
    }

    /**
     * Устанавливает URL пустой страницы
     * @param string $authorizationBlank URL пустой страницы
     * @return $this
     */
    public function setAuthorizationBlank(string $authorizationBlank)
    {
        $this->authorizationBlank   =   $authorizationBlank;
        return $this;
    }

    /**
     * Устанавливает ключ
     * @param string $key ключ
     * @return $this
     */
    public function setKey(string $key)
    {
        $this->key  =   $key;
        return $this;
    }

    /**
     * Устанавливает ДБ
     * @param \core\components\PDO\component $db ДБ
     * @return $this
     */
    public function setDB($db)
    {
        self::$db  =   $db;
        return $this;
    }

    private function registration()
    {

    }
}